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
defined('_JEXEC') or die('Restricted access');

/**
 * Joomla! Page Cache Plugin.
 *
 * @since  1.5
 */
class PlgContentSpeedcache_content extends JPlugin
{
    protected $autoloadLanguage = true;

    public function __construct(& $subject, $config)
    {
        parent::__construct($subject, $config);
        JFactory::getLanguage()->load('com_speedcache', JPATH_ADMINISTRATOR . '/components/com_speedcache/');
    }

    function onContentPrepareForm($form, $data)
    {
        $params = JComponentHelper::getParams('com_speedcache');
        if ($params->get('auto_add_url', '0')) {
            $app = JFactory::getApplication();
            $option = $app->input->get('option');
            if ($option == 'com_menus' && $app->isClient('administrator')) {
                JForm::addFormPath(__DIR__ . '/forms');
                $form->loadFile('speedcache_menus', false);
            }
        }

        if ($params->get('ajax_load_module_config', '0')) {
            $app = JFactory::getApplication();
            $option = $app->input->get('option');

            if ($app->isClient('administrator') && preg_match('/^com_(.*)modules$/', $option)) {
                JForm::addFormPath(__DIR__ . '/forms');
                $form->loadFile('speedcache_ajaxloadmodule', false);
            }
        }

        return true;
    }

    /**
     * Runs on content preparation
     *
     * @param   string $context The context for the data
     * @param   object $data An object containing the data for the form.
     *
     * @return  boolean
     *
     * @since   1.6
     */
    public function onContentPrepareData($context, $data)
    {
        // Check we are manipulating a valid form.
        if (!in_array($context, array('com_menus.item'))) {
            return true;
        }

        $dbo = JFactory::getDbo();

        if (is_array($data)) {
            $url = $this->getflink($data['id']);
            $query = 'SELECT * FROM #__speedcache_urls WHERE `url` ='.$dbo->quote($url);
            $dbo->setQuery($query);
            $dbo->execute();
            $params = $dbo->loadObject();
            if(!empty($params)){
                //load param form table url speedcache
                $data['params']['cacheguest'] = $params->cacheguest;
                $data['params']['cachelogged'] = $params->cachelogged;
                $data['params']['preloadguest'] = $params->preloadguest;
                $data['params']['preloadlogged'] = $params->preloadlogged;
                $data['params']['preloadperuser'] = $params->preloadperuser;
            }else{
                $config = JComponentHelper::getParams('com_speedcache');
                //load default params from config
                $cache_config = $config->get('cacheguest');
                if(!empty($cache_config)){
                    $data['params']['cacheguest'] = $config->get('cacheguest');
                    $data['params']['cachelogged'] = $config->get('cachelogged');
                    $data['params']['preloadguest'] = $config->get('preloadguest');
                    $data['params']['preloadlogged'] = $config->get('preloadlogged');
                    $data['params']['preloadperuser'] = $config->get('preloadperuser');
                }
            }

        }

        return true;
    }

    /**
     * Save on content
     *
     * @param   string $context The context for the data
     * @param   object $article An object containing the data
     * @param  isNew boolean
     *
     * @since   1.6
     */
    function onContentAfterSave($context, $article, $isNew)
    {
        // Check we are manipulating a valid form.
        if (!in_array($context, array('com_menus.item'))) {
            return true;
        }

        self::cleanCache('com_menus',1);

        $params = JComponentHelper::getParams('com_speedcache');
        $dbo = JFactory::getDbo();

        //get value from menus params
        $reg = new JRegistry();
        $reg->loadString($article->params);

        if ($params->get('auto_add_url', '0')) {
            $url = $this->getflink($article->id);
            if ($isNew) {
                //store data
                if (!empty($url)) {
                    if (!$this->urlExists($url)) {
                        $query = 'INSERT INTO #__speedcache_urls (`url`,`cacheguest`,`cachelogged`,`preloadguest`,`preloadlogged`,`preloadperuser`,`lifetime`,`specifictime`,`excludeguest`,`excludelogged`,`type`) VALUES (' . $dbo->quote($url) . ', ' . (int)$reg->get("cacheguest") . ' , ' . (int)$reg->get("cachelogged") . ', ' . (int)$reg->get("preloadguest") . ', ' . (int)$reg->get("preloadlogged") . ',' . (int)$reg->get("preloadperuser") . ',1,0,0,0 , "include") ON DUPLICATE KEY UPDATE `cacheguest` = 1';
                        $dbo->setQuery($query);
                        $dbo->execute();
                    }
                }
            } else{
                //edit menus
                if (!empty($url)) {
                    //update params if alias of menus not change
                    if ($this->urlExists($url)) {
                        $query = 'UPDATE #__speedcache_urls SET `cacheguest` = ' . (int)$reg->get("cacheguest") . ',`cachelogged` =' . (int)$reg->get("cachelogged") . ',`preloadguest` = ' . (int)$reg->get("preloadguest") . ',`preloadlogged` =' . (int)$reg->get("preloadlogged") . ', `preloadperuser` =' . (int)$reg->get("preloadperuser") . ' WHERE `url`=' . $dbo->quote($url);
                    }else{
                        $query = 'INSERT INTO #__speedcache_urls (`url`,`cacheguest`,`cachelogged`,`preloadguest`,`preloadlogged`,`preloadperuser`,`lifetime`,`specifictime`,`excludeguest`,`excludelogged`,`type`) VALUES (' . $dbo->quote($url) . ', ' . (int)$reg->get("cacheguest") . ' , ' . (int)$reg->get("cachelogged") . ', ' . (int)$reg->get("preloadguest") . ', ' . (int)$reg->get("preloadlogged") . ',' . (int)$reg->get("preloadperuser") . ',1,0,0,0 , "include") ON DUPLICATE KEY UPDATE `cacheguest` = 1';
                    }
                    $dbo->setQuery($query);
                    $dbo->execute();
                }
            }
        }
        return true;
    }

    /*
    * Return true in case an url already exist in database
    */
    public function urlExists($url)
    {
        $dbo = JFactory::getDbo();
        $query = 'SELECT COUNT(*) FROM #__speedcache_urls WHERE url=' . $dbo->quote($url);
        $dbo->setQuery($query);
        $dbo->execute();
        return $dbo->loadResult();
    }
    //get flink of menus
    public function getflink($menuitem){
        $dbo = JFactory::getDbo();
        $query = 'SELECT * FROM #__menu WHERE id=' . (int)$menuitem;
        $dbo->setQuery($query);
        $listMenus = $dbo->loadObjectList();
        $flink = '';
        $url = '';
        foreach ($listMenus as $Menus) {
            //Instanciate a site router
            $config = JFactory::getConfig();
            $router = JRouter::getInstance('site');
            $router->setMode($config->get('sef', 1));

            $Menus->params = new JRegistry($Menus->params);
            switch ($Menus->type) {
                case 'separator':
                case 'heading':
                    // There is no link for this cases but we need to show them
                    break;

                case 'url':
                    if ((strpos($Menus->link, 'index.php?') === 0) && (strpos($Menus->link, 'Itemid=') === false)) {
                        // If this is an internal Joomla link, ensure the Itemid is set.
                        $flink = $Menus->link . '&Itemid=' . $Menus->id;
                    } elseif (strpos($Menus->link, 'http') !== 0) {
                        $flink = $Menus->link;
                    } else {
                        continue 2;
                    }
                    break;

                case 'alias':
                    $flink = 'index.php?Itemid=' . $Menus->params->get('aliasoptions');
                    break;

                default:
                    $flink = 'index.php?Itemid=' . $Menus->id;
                    break;
            }

            if (isset($flink) && $flink) {
                $uri = $router->build($flink, true);
                $route = $uri->toString(array('path', 'query', 'fragment'));

                //Remove the administrator path
                $flink = str_replace(JUri::base(true) . '/', '', $route);

                //Add the site uri
                $flink = JUri::root() . $flink;
            }

        }
        //get path
        $url = substr($flink, strlen(JUri::root()));
        $url = trim($url, '/');
        return $url;
    }

    static protected function cleanCache($group = null, $client_id = 0)
    {
        $conf = JFactory::getConfig();

        $options = array(
            'defaultgroup' => ($group) ? $group : JFactory::getApplication()->input->get('option'),
            'cachebase' => ($client_id) ? JPATH_ADMINISTRATOR . '/cache' : $conf->get('cache_path', JPATH_SITE . '/cache'));

        $cache = JCache::getInstance('callback', $options);
        $cache->clean();
    }
}
