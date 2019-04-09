<?php
/**
 * Speedcache
 *
 * We developed this code with our hearts and passion.
 * We hope you found it useful, easy to understand and to customize.
 * Otherwise, please feel free to contact us at contact@joomunited.com *
 * @package Speedcache
 * @copyright Copyright (C) 2016 JoomUnited (https://www.joomunited.com). All rights reserved.
 * @license GNU General Public License version 2 or later; http://www.gnu.org/licenses/gpl-2.0.html
 */

defined('_JEXEC') or die;

/**
 * Joomla! Page Cache Plugin.
 *
 * @since  1.5
 */
class PlgSystemSpeedcache extends JPlugin
{
    protected $app = null;
    protected $db = null;

    protected $cache_key = null;

    protected $url = null;

    protected $cache_page_info = null;

    protected $check_url_cache = array();

    protected $username = null;

    protected $name = null;

    protected $email = null;

    protected $detect = null;

    protected $X1 = null;

    protected $body;

    private $user_replaced = false;

    protected $clean_tasks = array('save', 'apply', 'publish', 'unpublish', 'trash');

    /**
     * Constructor.
     *
     * @param   object &$subject The object to observe.
     * @param   array $config An optional associative array of configuration settings.
     *
     * @since   1.5
     */
    public function __construct(& $subject, $config)
    {
        parent::__construct($subject, $config);
        $this->url = $this->cleanUrl(JUri::getInstance()->toString());
        $params = JComponentHelper::getParams('com_speedcache');
        if (JFactory::getApplication()->isClient('site')) {
            // Lazy loading features;
            if ($params->get('lazy_loading', 0)) {
                $script = JUri::base(true) . '/plugins/system/speedcache/lazy_loading/js/jquery.lazyload.js';
                $styles = JUri::base(true) . '/plugins/system/speedcache/lazy_loading/css/jquery.lazyload.fadein.css';
                JFactory::getDocument()->addScript($script);
                JFactory::getDocument()->addStyleSheet($styles);
            }
            require_once 'Mobile-Detect-2.8.25/Mobile_Detect.php';
            $this->detect = new \Joomunited\SpeedCache\Mobile_Detect\Mobile_Detect;

        }

    }

    public function onAfterInitialise()
    {
        //Get speedcache component parameters
        $params = JComponentHelper::getParams('com_speedcache');
        //Do not use cache for some cases
        if ($this->app->isClient('administrator') || $this->app->input->getMethod() !== 'GET' || count($this->app->getMessageQueue())) {
            return;
        }
        //Login url with per user to preload cache
        $id = $this->app->input->server->get('HTTP_USERID', null);
        //check token preload
        $bearer = 'Bearer_' . $params->get('preloading_token', false);
        $isPreloadRequest = $this->app->input->server->get('HTTP_AUTHORIZATION_SC', null) === $bearer;

        if (!empty($id) && $isPreloadRequest) {
            //login per user
            $instance = JFactory::getUser($id);
            $instance->set('guest', 0);

            $session = JFactory::getSession();
            $session->set('user', $instance);

            JFactory::getApplication()->checkSession();
            $db = JFactory::getDbo();
            $query = $db->getQuery(true)
                ->update($db->quoteName('#__session'))
                ->set($db->quoteName('guest') . ' = ' . $db->quote($instance->get('guest')))
                ->set($db->quoteName('username') . ' = ' . $db->quote($instance->get('username')))
                ->set($db->quoteName('userid') . ' = ' . (int)$instance->get('id'))
                ->where($db->quoteName('session_id') . ' = ' . $db->quote($session->getId()));
            $db->setQuery($query);
            $db->execute();
        }

        $datas = false;
        $user = JFactory::getUser();
        //Get cache informations about the page
        // get new current page
        $pageInfos = $this->getCachesPage();

        //Check if this page should be cached if not fall back to joomla's default cache
        if (!$pageInfos || $this->cleanUrl($pageInfos->url) !== $this->url) {
            return;
        }
        if ($user->guest) {
            if (!$pageInfos->guest) {
                return;
            }
        } else {
            if (!$pageInfos->logged) {
                return;
            }
        }

        //Save current user values
        $this->username = $user->username;
        $this->name = $user->name;
        $this->email = $user->email;

        //Create the cache_key value
        $this->cache_key = $this->url;

        //Ignore parameter for url
        if (isset($pageInfos->ignoreparams) && $pageInfos->ignoreparams) {
            if (isset($pageInfos->purgeURL)) {
                $this->cache_key = $pageInfos->purgeURL;
            }
        }

        if (!$user->guest) {
            //In case user is connected check if we create a cache file per user
            if ($params->get('distinct_user_cache', '0')) {
                $this->cache_key .= '_' . $user->id;
            } else {
                $this->cache_key .= '_X';
            }
        } else {
            $this->cache_key .= '_0';
        }
        // Detect devides
        if ($this->detect->isMobile() && !$this->detect->isTablet()) {
            //        The first X will be D for Desktop cache
            //                                  M for Mobile cache
            //                                  T for Tablet cache
            if ($params->get('cache_mobile', false) == 0) {
                $this->X1 = 'D';
                $this->cache_key .= '_speed_cache_desktop';
            }
            if ($params->get('cache_mobile', false) == 1) {
                $this->X1 = 'M';
                $this->cache_key .= '_speed_cache_mobile';
            }
            if ($params->get('cache_mobile', false) == 2) {
                return;
            }
        } elseif ($this->detect->isTablet()) {
            if ($params->get('cache_tablet', false) == 0) {
                $this->X1 = 'D';
                $this->cache_key .= '_speed_cache_desktop';
            }
            if ($params->get('cache_tablet', false) == 1) {
                $this->X1 = 'T';
                $this->cache_key .= '_speed_cache_tablet';
            }
            if ($params->get('cache_tablet', false) == 2) {
                return;
            }
        } else {
            if ($params->get('cache_desktop', false) == 0) {
                $this->X1 = 'D';
                $this->cache_key .= '_speed_cache_desktop';
            }
            if ($params->get('cache_desktop', false) == 1) {
                return;
            }
        }

        //Check if cache file exists and if it's expired
        $cacheFile = JPATH_CACHE . DIRECTORY_SEPARATOR . 'speedcache';
        $cacheFile .= DIRECTORY_SEPARATOR . md5($this->cache_key) . '.php';

        if (file_exists($cacheFile) && !$isPreloadRequest) {
            if (file_exists(JPATH_PLUGINS . '/system/piwik/piwik.php')) {
                require_once JPATH_PLUGINS . '/system/piwik/piwik.php';
                if (JPluginHelper::isEnabled('system', 'piwik')) {
                    PlgSystemPiwik::callPiwik();
                }
            }

            $modifiedDate = filemtime($cacheFile);
            $timelife = 0;
            if (isset($pageInfos->lifetime)) {
                if ($pageInfos->lifetime == '1') {
                    $timelife = $params->get('cache_lifetime', 1440) * 60;
                } else {
                    $timelife = $pageInfos->specifictime * 60;
                }
            } else {
                $timelife = $params->get('cache_lifetime', 1440) * 60;
            }
            //Check if file modification time exceed max cache time
            if ($modifiedDate + $timelife > time()) {
                // If the etag matches the page id
                // from Joomla 3.5 source code
                if (!headers_sent() && isset($_SERVER['HTTP_IF_NONE_MATCH'])) {
                    //Get browser etag
                    $etag = stripslashes($_SERVER['HTTP_IF_NONE_MATCH']);
                    //Check if browser etag is same than generated etag
                    if ($etag == md5($this->cache_key . $modifiedDate . JSession::getFormToken()) ||
                        $modifiedDate == strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE'])) {
                        //Return directly not modified header and exit
                        if ($params->get('use_browser_cache', false)) {
                            header('HTTP/1.x 304 Not Modified', true);
                            $this->app->close();
                        }
                    }
                }

                //Load file cache content and remove security string
                $datas = file_get_contents($cacheFile);
                $datas = substr($datas, strlen('<?php die("Access Denied"); ?>'));
            }
        }

        //If we have cached datas lets process it
        if ($datas !== false) {
            $unserializedDatas = unserialize($datas);
            if ($unserializedDatas === false) {
                return;
            }

            //Search for a replaced string in the cached page and replace it with the proper values.
            $token = JSession::getFormToken();
            $search = array(
                '###speedCACHE#TOKEN#!speedCACHE###',
                '###speedCACHE#EMAIL#!speedCACHE###',
                '###speedCACHE#USERNAME#!speedCACHE###',
                '###speedCACHE#NAME#!speedCACHE###'
            );
            $body = str_replace(
                $search,
                array($token, $this->email, $this->username, $this->name),
                $unserializedDatas['body']
            );

            //Set headers
            foreach ($unserializedDatas['headers'] as $header) {
                $this->app->setHeader($header['name'], $header['value']);
            }

            //Update the etag reference
            $modifiedDate = filemtime($cacheFile);
            if ($params->get('use_browser_cache', false)) {
                $this->app->setHeader('ETag', md5($this->cache_key . $modifiedDate . JSession::getFormToken()), true);
            }

            // If gzip compression is enabled in configuration and the server is compliant, compress the output.
            if ($this->app->get('gzip') &&
                !ini_get('zlib.output_compression') &&
                (ini_get('output_handler') != 'ob_gzhandler')) {
                //Compress body if requested
                $supported = array(
                    'x-gzip' => 'gz',
                    'gzip' => 'gz',
                    'deflate' => 'deflate'
                );

                // Get the supported encoding.
                $encodings = array_intersect($this->app->client->encodings, array_keys($supported));

                // Check header if exist
                $header = $this->app->getHeaders();
                $check_gzip_exist = false;
                if(!empty($header)) {
                    foreach ($header as $k => $v) {
                        if ($v['name'] == 'Content-Encoding') {
                            $check_gzip_exist = true;
                        }
                    }
                }

                if (!empty($encodings) && !headers_sent() && (connection_status() === CONNECTION_NORMAL)) {
                    // Iterate through the encodings and attempt to
                    // compress the data using any found supported encodings.
                    foreach ($encodings as $encoding) {
                        if (($supported[$encoding] == 'gz') || ($supported[$encoding] == 'deflate')) {
                            // Verify that the server supports gzip compression
                            // before we attempt to gzip encode the data.
                            if (!extension_loaded('zlib') || ini_get('zlib.output_compression')) {
                                continue;
                            }

                            // Attempt to gzip encode the data with an optimal level 4.
                            $gzdata = gzencode($body, 4, ($supported[$encoding] == 'gz') ? FORCE_GZIP : FORCE_DEFLATE);

                            // If there was a problem encoding the data just try the next encoding scheme.
                            if ($gzdata === false) {
                                continue;
                            }


                            // Set the encoding headers.
                            if (!$check_gzip_exist) {
                                $this->app->setHeader('Content-Encoding', $encoding);
                            }


                            // Replace the output with the encoded data.
                            $body = $gzdata;

                            // Compression complete, let's break out of the loop.
                            break;
                        }
                    }
                }
            }

            if (!$body) {
                return;
            }

            if ($params->get('cache_header', 0)) {
                $this->app->setHeader('Cache-Provider', 'SpeedCache,'.$this->X1 . 'E' );
            }

            $this->app->setBody($body);
            echo $this->app->toString();

            $this->app->close();
        }

        //Use a workaround to replace users values if the same file is used for all users
        if (!$user->guest && !$params->get('distinct_user_cache', '0')) {
            //Replace the user values by our replacement string
            $this->user_replaced = true;
            $user->username = '###speedCACHE#USERNAME#!speedCACHE###';
            $user->name = '###speedCACHE#NAME#!speedCACHE###';
            $user->email = '###speedCACHE#EMAIL#!speedCACHE###';
        }
    }

    /**
     * Trigger on after render
     */
    public function speedcacheOnAfterRender()
    {
        $this->body = $this->app->getBody();
        $params = JComponentHelper::getParams('com_speedcache');
        //Return html after scan url on backend
        if ($this->app->input->get('scbackend') == '2') {
            echo $this->body;
            while (@ob_end_flush());
            exit;
        }

        if (JFactory::getApplication()->isClient('site')) {
            // Ajax load module
            if ($params->get('ajax_load_module_config', '0')) {
                // Check if it is not httml, dont need
                if (preg_match('#</html>#i', $this->body)) {
                    include_once(dirname(__FILE__) . '/ajax_load_modules/ajax_load_modules.php');
                    $loadModule = new SCAjaxLoadModules();
                    $this->body = $loadModule->setModules($this->body);
                }
            }
            // Lazy loading features;
            if ($params->get('lazy_loading', 0)) {
                $check_enable = $this->checkLazyLoading($params);

                if ($check_enable) {
                    include_once(dirname(__FILE__) . '/lazy_loading/lazy_loading.php');
                    if (!defined('SC_SITE_URL')) {
                        define('SC_SITE_URL', JUri::base());
                    }
                    $lazyLoading = new SCLazyLoading($this->body);

                    $this->body = $lazyLoading->setup();
                }
            }

            //run minify here!
            $this->body = $this->runMinifyHtml($this->body);

            // run CDN
            if ($params->get('cdn_active', 0)) {
                include_once(dirname(__FILE__) . '/cdn_integration/cdn_rewrite.php');
                $cdn = new SCCDNRewrite();
                $this->body = $cdn->rewrite($this->body);
            }
        }
        //If user values has been replaced we need to put it back to the original value
        if (!$this->user_replaced) {
            $this->app->setBody($this->body);
        } else {
            //Replace string and store it into a variable
            $search = array(
                '###speedCACHE#EMAIL#!speedCACHE###',
                '###speedCACHE#USERNAME#!speedCACHE###',
                '###speedCACHE#NAME#!speedCACHE###'
            );
            //Revert the user values to the original
            $user = JFactory::getUser();
            $user->username = $this->username;
            $user->name = $this->name;
            $user->email = $this->email;
            //Replace in the content served the user details
            $this->app->setBody(str_replace($search, array($this->email, $this->username, $this->name), $this->body));
        }
    }

    /**
     * After render.
     *
     * @return   void
     *
     * @since   1.5
     */
    public function speedcacheOnAfterRespond()
    {
        $params = JComponentHelper::getParams('com_speedcache');
        //After cache is cleared, automatic preload based the url list to cache
        $session = JFactory::getSession();
        if ($session->get('speedcache_preload', false)) {
            $path = JPATH_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_speedcache';
            $path .= DIRECTORY_SEPARATOR . 'helpers' . DIRECTORY_SEPARATOR . 'speedcache.php';
            require_once($path);
            speedCacheHelper::processPreload();
            $session->set('speedcache_preload', false);
        }
        $user = JFactory::getUser();

        //Check case we don't cache anything
        if ($this->app->isClient('administrator') || $this->app->input->getMethod() !== 'GET' || count($this->app->getMessageQueue())) {
            return;
        }

        //Get current page speedcache informations
        $pageInfos = $this->getCachesPage();
        //Check if this page should be saved into database
        //In case this url is not in the database and auto index is enabled
        if ($pageInfos === null) {
            $this->db = JFactory::getDbo();
            $query = 'INSERT IGNORE INTO #__speedcache_urls SET url=';
            $query .= $this->db->quote(trim($this->url, '/')) . ', cacheguest=1,cachelogged=0';
            $this->db->setQuery($query);
            $this->db->execute();
        }
        if (!$pageInfos || $this->cleanUrl($pageInfos->url) !== $this->url) {
            return;
        }
        if ($user->guest) {
            if (!$pageInfos->guest) {
                return;
            }
        } else {
            if (!$pageInfos->logged) {
                return;
            }
        }

        // Detect devides
        if ($this->detect->isMobile() && !$this->detect->isTablet()) {
            //        The first X will be D for Desktop cache
            //                                  M for Mobile cache
            //                                  T for Tablet cache
            if ($params->get('cache_mobile', false) == 2) {
                return;
            }
        } elseif ($this->detect->isTablet()) {
            if ($params->get('cache_tablet', false) == 2) {
                return;
            }
        } else {
            if ($params->get('cache_desktop', false) == 1) {
                return;
            }
        }

        //Get current page full html response
        $headers = $this->app->getHeaders();
        if ($this->user_replaced) {
            $body = $this->body;
        } else {
            $body = $this->app->getBody();

            //Decode gzipped content
            foreach ($headers as $headerKey => $headerValue) {
                if ($headerValue['name'] === 'Content-Encoding') {
                    switch ($headerValue['value']
                    ) {
                        case 'gzip':
                        case 'x-gzip':
                        case 'deflate':
                            if (!extension_loaded('zlib')) {
                                return;
                            }
                            $decodedBody = gzdecode($body);
                            if (!$decodedBody) {
                                //If we can't decode do nothing
                                return;
                            }
                            $body = $decodedBody;

                            //Remove the encoding as it's not anymore encoded
                            unset($headers[$headerKey]);

                            break 2;
                    }
                }
            }
        }
        //Replace the token by our own value
        $token = JSession::getFormToken();
        $search = '###speedCACHE#TOKEN#!speedCACHE###';
        $body = str_replace($token, $search, $body);
        $datas = serialize(array('key' => $this->cache_key, 'body' => $body, 'headers' => $headers));

        $cacheFile = JPATH_CACHE . DIRECTORY_SEPARATOR . 'speedcache';
        $cacheFile .= DIRECTORY_SEPARATOR . md5($this->cache_key) . '.php';
        $cacheDirectory = JPATH_CACHE . DIRECTORY_SEPARATOR . 'speedcache';
        if (!file_exists($cacheDirectory)) {
            mkdir($cacheDirectory, 0777, true);
        }
        file_put_contents($cacheFile, '<?php die("Access Denied"); ?>' . $datas);
    }

    //Check after routin if the cache need to be cleared
    public function onAfterRoute()
    {
        //Only save on post request
        if ($this->app->input->getMethod() !== 'POST') {
            return;
        }
        $params = JComponentHelper::getParams('com_speedcache');
        //if clean on admin side is not enabled quit
        if ($this->app->isClient('administrator') && !$params->get('clear_on_admin_tasks', 1)) {
            return;
        }
        //if clean on frontend side is not enabled quit
        if ($this->app->isClient('site') && !$params->get('clear_on_frontend_tasks')) {
            return;
        }
        $task = $this->app->input->get('task');
        if (!$task) {
            return;
        }
        $task = explode('.', $task);
        if (count($task) > 1 && !in_array($task[1], $this->clean_tasks)) {
            return;
        } elseif (count($task) === 1 && !in_array($task[0], $this->clean_tasks)) {
            return;
        }
        //This is a save task let's clear our cache
        jimport('joomla.filesystem.folder');
        $cacheFolder = JPATH_SITE . DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR . 'speedcache';
        if (file_exists($cacheFolder)) {
            JFolder::delete($cacheFolder);
        }
        //Clear Joomla cache is required
        if ($params->get('clear_joomla_cache', 1)) {
            $conf = JFactory::getConfig();

            $options = array(
                'defaultgroup' => '',
                'storage' => $conf->get('cache_handler', ''),
                'caching' => true,
                'cachebase' => $conf->get('cache_path', JPATH_SITE . '/cache')
            );

            $cache = JCache::getInstance('', $options);
            $cache->clean();
        }

        //We need to recreate the zoo cache to prevent alert message
        // when a plugin create an instance of ZOO app before we clean the cache
        if (JComponentHelper::isInstalled('com_zoo') && JComponentHelper::getComponent('com_zoo', true)->enabled) {
            JFolder::create(JPATH_SITE . DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR . 'com_zoo');
        }

        if ($params->get('cache_preloading_after_save')) {
            $session = JFactory::getSession();
            $session->set('speedcache_preload', true);
        }
    }

    /**
     * get url for cache or exclude cache
     */
    public function getCachesPage()
    {
        $thisurl = $this->url;
        $url = substr($thisurl, strlen(JUri::root()));

        $this->db = JFactory::getDbo();
        $pageInfo = new stdClass();
        $pageInfo->url = $this->url;

        //Retrieve exclude cache exact url
        $query = 'SELECT * FROM #__speedcache_urls WHERE type = "exclude" AND url =' . $this->db->quote($url);
        $this->db->setQuery($query);
        $excludeURL = $this->db->loadObject();
        if (!empty($excludeURL)) {
            if ($excludeURL->excludeguest) {
                $pageInfo->guest = false;
            }
            if ($excludeURL->excludelogged) {
                $pageInfo->logged = false;
            }
            if (isset($pageInfo->guest) && isset($pageInfo->logged)) {
                //turn off joomla cache if exclude cache for this url
                JFactory::getConfig()->set('caching', 0);
                return $pageInfo;
            }
        }

        //Retrieve exclude cache rules
        $query = 'SELECT * FROM #__speedcache_urls WHERE type="rules_exclude"';
        $this->db->setQuery($query);
        $excludeRules = $this->db->loadAssocList();
        if (!empty($excludeRules)) {
            foreach ($excludeRules as $v) {
                $trans = array(
                    '.' => '\.',
                    '+' => '\+',
                    '?' => '\?',
                    '[' => '\[',
                    '^' => '\^',
                    ']' => '\]',
                    '$' => '\$',
                    '(' => '\(',
                    ')' => '\)',
                    '{' => '\{',
                    '}' => '\}',
                    '=' => '\=',
                    '<' => '\<',
                    '>' => '\>',
                    '|' => '\|',
                    ':' => '\:',
                    '-' => '\-');
                $v['url'] = strtr($v['url'], $trans);

                if (strpos($v['url'], '/*') === strlen($v['url']) - 2) {
                    // Url ends by /*
                    $regex_end = '/(?:$|\/?.*)';
                    $v['url'] = substr($v['url'], 0, strlen($v['url']) - 2);
                    $v['url'] = $v['url'] . $regex_end;
                } else {
                    // Replace all other occurrences of *
                    $v['url'] = str_replace('*', '(?:.*)', $v['url']);
                }
                if ((@preg_match('@' . $v['url'] . '@', $this->url, $matches) > 0)) {
                    //if current url indifferent with the table, get current url
                    if (!isset($pageInfo->guest) && $v['excludeguest']) {
                        $pageInfo->guest = false;
                    }
                    if (!isset($pageInfo->logged) && $v['excludelogged']) {
                        $pageInfo->logged = false;
                    }
                }
            }
            if (isset($pageInfo->guest) && isset($pageInfo->logged)) {
                //turn off joomla cache if exclude cache for this rules
                JFactory::getConfig()->set('caching', 0);
                return $pageInfo;
            }
        }
        //Retrieve include cache exact url
        $query = 'SELECT * FROM #__speedcache_urls WHERE type = "include" AND url =' . $this->db->quote($url);
        $this->db->setQuery($query);
        $includeURL = $this->db->loadObject();
        if (!empty($includeURL)) {
            if (isset($includeURL->lifetime)) {
                $pageInfo->lifetime = $includeURL->lifetime;
            }
            if (isset($includeURL->specifictime)) {
                $pageInfo->specifictime = $includeURL->specifictime;
            }
            if (!isset($pageInfo->guest) && $includeURL->cacheguest) {
                $pageInfo->guest = true;
            }

            if (!isset($pageInfo->logged) && $includeURL->cachelogged) {
                $pageInfo->logged = true;
            }
            if (isset($pageInfo->guest) && isset($pageInfo->logged)) {
                return $pageInfo;
            }
        }

        //Retrieve include cache rules
        $query = 'SELECT * FROM #__speedcache_urls WHERE type="rules_include"';
        $this->db->setQuery($query);
        $includeRules = $this->db->loadAssocList();
        $rules = '';
        if (!empty($includeRules)) {
            foreach ($includeRules as $v) {
                $rules = $v['url'];
                $trans = array(
                    '.' => '\.',
                    '+' => '\+',
                    '?' => '\?',
                    '[' => '\[',
                    '^' => '\^',
                    ']' => '\]',
                    '$' => '\$',
                    '(' => '\(',
                    ')' => '\)',
                    '{' => '\{',
                    '}' => '\}',
                    '=' => '\=',
                    '<' => '\<',
                    '>' => '\>',
                    '|' => '\|',
                    ':' => '\:');
                $v['url'] = strtr($v['url'], $trans);
                $regex_end = '';
                if (strpos($v['url'], '/*') === strlen($v['url']) - 2) {
                    // Url ends by /*
                    $regex_end = '/(?:$|\/?.*)';
                    $v['url'] = substr($v['url'], 0, strlen($v['url']) - 2);
                    $v['url'] = $v['url'] . $regex_end;
                } else {
                    // Replace all other occurrences of *
                    $v['url'] = str_replace('*', '(?:.*)', $v['url']);
                }

                if ((@preg_match('@' . $v['url'] . '@', $this->url, $matches) > 0)) {
                    if (!isset($pageInfo->lifetime)) {
                        $pageInfo->lifetime = $v['lifetime'];
                        $pageInfo->specifictime = $v['specifictime'];
                    }
                    //if current url indifferent with the table, get current url
                    if (!isset($pageInfo->guest) && $v['cacheguest']) {
                        $pageInfo->guest = true;
                    }

                    if (!isset($pageInfo->logged) && $v['cachelogged']) {
                        $pageInfo->logged = true;
                    }

                    if (isset($v['ignoreparams']) && $v['ignoreparams']) {
                        $pageInfo->ignoreparams = true;
                        $sub_url =  preg_replace("/(\*.*)|((\/|\.|\+|\?|\[|\^|\]|\$|\(|\)|\{|\}|\=|\<|\>|\||\:)\*.*)/i", '', $rules);
                        if (!empty($sub_url)) {
                            $purgeURL = substr($this->url, 0, strpos($this->url, $sub_url) + strlen($sub_url));
                            $pageInfo->purgeURL = $purgeURL;
                        }
                    }
                }
            }

            if (isset($pageInfo->guest) && isset($pageInfo->logged)) {
                return $pageInfo;
            }
        }

        if (isset($pageInfo->guest) && !isset($pageInfo->logged)) {
            $pageInfo->logged = false;
            if (!isset($pageInfo->lifetime)) {
                $pageInfo->lifetime = 1;
                $pageInfo->specifictime = 0;
            }
            //turn off joomla cache if exclude cache for this url
            JFactory::getConfig()->set('caching', 0);
            return $pageInfo;
        } elseif (!isset($pageInfo->guest) && isset($pageInfo->logged)) {
            $pageInfo->guest = false;
            if (!isset($pageInfo->lifetime)) {
                $pageInfo->lifetime = 1;
                $pageInfo->specifictime = 0;
            }
            //turn off joomla cache if exclude cache for this url
            JFactory::getConfig()->set('caching', 0);
            return $pageInfo;
        }
        return false;
    }

    /**
     * Url to purge string
     * @param $url
     * @return string
     */
    protected function cleanUrl($url)
    {
        return trim($url, "/ \t\n\r\0\x0B");
    }

    /**
     * Execute minify
     * @param $content
     * @return mixed
     */
    protected function runMinifyHtml($content)
    {
        $app = JFactory::getApplication();
        $params = JComponentHelper::getParams('com_speedcache');
        $classes = $jsTurnOn = $cssTurnOn = array();
        $jsExcludes = $cssExcludes = '';
        if ($app->isClient('site')) {
            define('SC_JOOMLA_SITE_URL', JUri::base());
            define('SC_JPATH_CACHE_PATH', JPATH_CACHE . '/speedcache-minify/');
            define('SC_CACHEFILE_PREFIX', 'sc_');

            include_once(dirname(__FILE__) . '/minifications/sc-minification-cache.php');
            if (SCMinificationCache::createCacheMinificationFolder()) {
                $fontMinify = $this->getTypeMinify(0);
                $cssMinify = $this->getTypeMinify(1);
                $cssMinify = array_merge($cssMinify, $fontMinify);
                $jsMinify = $this->getTypeMinify(2);
                // Load our base class
                $deferJS = $deferCSS = $cacheExternal = false;
                include_once(dirname(__FILE__) . '/minifications/sc-minification-base.php');

                if (!empty($jsMinify) ||
                    $params->get('minify_group_js', 0) ||
                    $params->get('defer_js', 0) ||
                    $params->get('cache_external_script', 0)
                ) {
                    include_once(dirname(__FILE__) . '/minifications/sc-minification-scripts.php');
                    if (!class_exists('JSMin')) {
                        @include(dirname(__FILE__) . '/minifications/minify/minify-2.1.7-jsmin.php');
                    }
                    $classes[] = 'SCMinificationScripts';
                    foreach ($jsMinify as $v) {
                        $jsTurnOn[] = $v['file'];
                    }
                    if ($params->get('minify_exclusion_group', 0)) {
                        $jsExcludes = $params->get('minify_exclusion_group', 0)->js;
                    }
                    if ($params->get('defer_js', 0)) {
                        $deferJS = true;
                    }
                }

                if (!empty($cssMinify) ||
                    $params->get('minify_group_css', 0) ||
                    $params->get('minify_group_fonts', 0) ||
                    $params->get('defer_css', 0)) {
                    include_once(dirname(__FILE__) . '/minifications/sc-minification-styles.php');
                    if (!class_exists('CSSmin')) {
                        @include(dirname(__FILE__) . '/minifications/minify/yui-php-cssmin-2.4.8-4_fgo.php');
                    }
                    $classes[] = 'SCMinificationStyles';
                    foreach ($cssMinify as $v) {
                        $cssTurnOn[] = $v['file'];
                    }
                    if ($params->get('minify_exclusion_group', 0)) {
                        $cssExcludes = $params->get('minify_exclusion_group', 0)->css;
                    }
                    if ($params->get('defer_css', 0)) {
                        $deferCSS = true;
                    }
                }

                if (stripos($content, "<html") === false ||
                    stripos($content, "<html amp") !== false ||
                    stripos($content, "<html ⚡") !== false ||
                    stripos($content, "<xsl:stylesheet") !== false) {
                    return $content;
                }
                $groupCss = $groupJs = $groupFonts = false;
                if ($params->get('minify_group_css', 0)) {
                    $groupCss = true;
                }
                if ($params->get('minify_group_js', 0)) {
                    $groupJs = true;
                }
                if ($params->get('minify_group_fonts', 0)) {
                    $groupFonts = true;
                }
                if ($params->get('cache_external_script', 0)) {
                    $cacheExternal = true;
                }

                $js_exclude_before = "s_sid, smowtion_size, sc_project, WAU_, wau_add,";
                $js_exclude_before .= "comment-form-quicktags, edToolbar, ch_client, seal.js";
                // Set some options
                $classoptions = array(
                    'SCMinificationScripts' => array(
                        'justhead' => false,
                        'forcehead' => true,
                        'trycatch' => false,
                        'defer' => $deferJS,
                        'js_exclude' => $js_exclude_before,
                        'include_inline' => true,
                        'groupjs' => $groupJs,
                        'turn_on_js' => $jsTurnOn,
                        'jsExcludes' => $jsExcludes,
                        'cache_external' => $cacheExternal
                    ),
                    'SCMinificationStyles' => array(
                        'justhead' => false,
                        'defer' => $deferCSS,
                        'defer_inline' => false,
                        'inline' => false,
                        'css_exclude' => "admin-bar.min.css, dashicons.min.css",
                        'include_inline' => true,
                        'nogooglefont' => false,
                        'groupcss' => $groupCss,
                        'groupfonts' => $groupFonts,
                        'turn_on_css' => $cssTurnOn,
                        'cssExcludes' => $cssExcludes
                    )
                );

                if (!empty($classes)) {
                    // Run the classes
                    foreach ($classes as $name) {
                        $instance = new $name($content);
                        if ($instance->read($classoptions[$name])) {
                            $instance->minify();
                            $instance->cache();
                            $content = $instance->getcontent();
                        }
                        unset($instance);
                    }
                }
            }
        }
        return $content;
    }


    /**
     * Get active minify
     * @param $type
     * @return mixed
     */
    public function getTypeMinify($type)
    {
        $db = JFactory::getDbo();
        $query = 'SELECT * FROM #__speedcache_minify_file WHERE minify=1 AND type=' . $db->quote((int)$type);
        $db->setQuery($query);
        return $db->loadAssocList();
    }

    /**
     * Determines if SSL is used.
     *
     * @since 2.6.0
     * @since 4.6.0 Moved from functions.php to load.php.
     *
     * @return bool True if SSL, otherwise false.
     */
    public static function isSsl()
    {
        if (isset($_SERVER['HTTPS'])) {
            if ('on' == strtolower($_SERVER['HTTPS'])) {
                return true;
            }

            if ('1' == $_SERVER['HTTPS']) {
                return true;
            }
        } elseif (isset($_SERVER['SERVER_PORT']) && ('443' == $_SERVER['SERVER_PORT'])) {
            return true;
        }
        return false;
    }

    public function speedcacheOnBeforeRender()
    {
        $app = JFactory::getApplication();
        if ($app->isAdmin()) {
            return;
        }
        $document = JFactory::getDocument();
        $params = JComponentHelper::getParams('com_speedcache');

        if ($params->get('ajax_load_module_config', '0')) {
            $loader_link = 'plugins/system/speedcache/ajax_load_modules/image/modajaxloader.gif';
            $inlinescript = "
                var speedcache_base_url = '" . JURI::base() . "';
                var loader_link = '".$loader_link."';
            ";
            $script = JUri::base(true) . '/plugins/system/speedcache/ajax_load_modules/js/modajaxloader.js';
            $document->addScript($script);
            $document->addScriptDeclaration($inlinescript);
        }

    }

    /**
     * Clear cache
     */
    public function speedcacheOnClearCache()
    {

        JModelLegacy::addIncludePath(JPATH_ROOT . '/administrator/components/com_cache/models');
        $model = JModelLegacy::getInstance('cache', 'CacheModel');

        if (!empty($model)) {
            $mCache = $model->getCache(0);

            foreach ($mCache->getAll() as $cache) {
                $mCache->clean($cache->group);
            }
        }
    }

    /**
     * Check lazy loading enable with page
     *
     * @param object $params List settings
     *
     * @return boolean
     */
    private function checkLazyLoading($params)
    {
        $domain = (((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ||
                    $_SERVER['SERVER_PORT'] === 443) ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'];
        //decode url with russian language
        $current_url = $domain . rawurldecode($_SERVER['REQUEST_URI']);
        $list_exclude = $params->get('exclude_lazy_loading', array());
        $list_include = $params->get('include_lazy_loading', array());
        // Check Exclude lazyloading
        if (!empty($list_exclude)) {
            $result = $this->checkRules($list_exclude, $current_url, 'exclude');
            if ($result !== 'continue') {
                return $result;
            }
        }

        // Check enable lazyloading with include
        if (!empty($list_include)) {
            $result = $this->checkRules($list_include, $current_url, false);
            if ($result !== 'continue') {
                return $result;
            }
        }

        return false;
    }

    /**
     * Check lazy loading enable with page
     *
     * @param array  $lists       List settings
     * @param string $current_url Current url
     * @param string $type        Type check
     *
     * @return boolean|string
     */
    public function checkRules($lists, $current_url, $type)
    {
        $check = true;
        if (!empty($type) && $type === 'exclude') {
            $check = false;
        }

        if (!empty($lists)) {
            foreach ($lists as $v) {
                if (empty($v)) {
                    continue;
                }
                // Clear blank character
                $v = trim($v);
                if (preg_match('/(\/?\&?\(\.?\*\)|\/\*|\*)$/', $v, $matches)) {
                    // End of rules is /*, /(*) , /(.*)
                    $pattent = substr($v, 0, strpos($v, $matches[0]));
                    if ($v[0] === '/') {
                        // A path of exclude url with regex
                        if ((preg_match('@' . $pattent . '@', $current_url, $matches) > 0)) {
                            return $check;
                        }
                    } else {
                        // Full exclude url with regex
                        if (strpos($current_url, $pattent) !== false) {
                            return $check;
                        }
                    }
                } else {
                    if ($v[0] === '/') {
                        // A path of exclude
                        if ((preg_match('@' . $v . '@', $current_url, $matches) > 0)) {
                            return $check;
                        }
                    } else {
                        // Whole path
                        if ($v === $current_url) {
                            return $check;
                        }
                    }
                }
            }
        }
        return 'continue';
    }
}
