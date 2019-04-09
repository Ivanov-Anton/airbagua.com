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
 * speedcache Component speedcache Model
 *
 * @since  1.6
 */
class speedcacheControllerDashboard extends JControllerLegacy
{
    protected $return;

    /*
     * Perform all check actions needed for the dasboard
     */
    public function check()
    {
        $this->return = array(
            'curl_enable' => true,
            'gzip' => 'unknown',
            'expires_headers' => 'unknown',
            'expires_module' => 'unknown'
        );

        if (!function_exists('curl_init')) {
            $this->return['curl_enable'] = false;

            //No need to perform other tasks which need curl
            $this->save();
        }

        $this->return['gzip'] = $this->checkGzip();

        $this->return['expires_headers'] = $this->checkExpiresHeaders();

        $this->return['expires_module'] = $this->checkApacheModuleExists('mod_expires');

        JFactory::getDocument()->setMimeEncoding('application/json');
        echo json_encode($this->return);
        jexit();
    }

    /**
     * Call the front home page to retrieve headers and
     * check if it contains the encoding gzip
     * @return bool
     */
    private function checkGzip()
    {
        $ch = curl_init(JUri::root());
        curl_setopt($ch, CURLOPT_ENCODING, "gzip");
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, true);
        $response = curl_exec($ch);
        $info = curl_getinfo($ch);

        $headers = substr($response, 0, $info['header_size']);

        foreach (explode("\r\n", $headers) as $header) {
            $headerContent = explode(':', $header);
            if (trim($headerContent[0]) === 'Content-Encoding') {
                if (trim($headerContent[1]) !== 'gzip') {
                    return false;
                }
                return true;
            }
        }
        return false;
    }

    /**
     * Call some files to check expires headers are set
     * for this type of files
     * @return array
     */
    private function checkExpiresHeaders()
    {
        $generalOk = true;

        //Files to check
        $filetypes = array(
            'png' => array('expires' => false, 'date' => false),
            'jpg' => array('expires' => false, 'date' => false),
            'gif' => array('expires' => false, 'date' => false),
            'css' => array('expires' => false, 'date' => false),
            'js' => array('expires' => false, 'date' => false)
        );
        foreach ($filetypes as $filetype => $value) {
            $ch = curl_init(JUri::root() . 'components/com_speedcache/assets/file.' . $filetype);
            $ua = 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US) AppleWebKit/525.13 (KHTML, like Gecko) Chrome/0.A.B.C Safari/525.13';
            curl_setopt($ch, CURLOPT_ENCODING, "gzip");
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_USERAGENT, $ua);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HEADER, true);
            $response = curl_exec($ch);
            $info = curl_getinfo($ch);

            $headers = substr($response, 0, $info['header_size']);

            foreach (explode("\r\n", $headers) as $header) {
                $headerContent = explode(':', $header, 2);
                if (trim($headerContent[0]) === 'Expires') {
                    $filetypes[$filetype]['expires'] = trim($headerContent[1]);
                } elseif (trim($headerContent[0]) === 'Date') {
                    $filetypes[$filetype]['date'] = trim($headerContent[1]);
                }
            }
            //Set when one of the files doesn't have expireheader set
            if (!$filetypes[$filetype]['expires']) {
                $generalOk = false;
            }
        }
        if (!file_exists(JPATH_ROOT . DIRECTORY_SEPARATOR . '.htaccess')) {
            $generalOk = true;
        }

        return array('expires_headers' => $generalOk, 'filetypes' => $filetypes);
    }

    /*
     * Set the Joomla global option to true
     */
    public function fixGzip()
    {
        $this->canAdmin();

        if (!JFactory::getApplication()->get('gzip', 0)) {
            $this->setGlobalParameter('gzip', '1');
        }

        $this->cleanCaches();

        JFactory::getSession()->set(
            'speedache_message',
            array(
                'type' => 'success',
                'message' => JText::sprintf('COM_SPEEDCACHE_DASHBOARD_GZIP_PATCH_SUCCESS')
            )
        );
        echo json_encode(array('status' => 'success'));
        jexit();
    }

    /*
     * Set the Joomla global option to true
     */
    public function fixCaching()
    {
        $this->canAdmin();

        if (!JFactory::getApplication()->get('caching', 0)) {
            $this->setGlobalParameter('caching', '1');
        }

        $this->cleanCaches();

        JFactory::getSession()->set(
            'speedache_message',
            array(
                'type' => 'success',
                'message' => JText::sprintf('COM_SPEEDCACHE_DASHBOARD_CACHING_PATCH_SUCCESS')
            )
        );
        echo json_encode(array('status' => 'success'));
        jexit();
    }

    /*
     * Set the Joomla global option to true
     */
    public function fixCachetime()
    {
        $this->canAdmin();

        if (JFactory::getApplication()->get('cachetime') < 30) {
            $this->setGlobalParameter('cachetime', '30');
        }

        $this->cleanCaches();

        JFactory::getSession()->set(
            'speedache_message',
            array(
                'type' => 'success',
                'message' => JText::sprintf('COM_SPEEDCACHE_DASHBOARD_CACHE_TIME_PATCH_SUCCESS')
            )
        );
        echo json_encode(array('status' => 'success'));
        jexit();
    }

    /*
     * Set the browser cache speedcache config to false
     */
    public function fixBrowserCache()
    {
        $this->canAdmin();

        $this->setComponentParameter('use_browser_cache', '1');

        $this->cleanCaches();

        JFactory::getSession()->set(
            'speedache_message',
            array(
                'type' => 'success',
                'message' => JText::sprintf('COM_SPEEDCACHE_DASHBOARD_BROWSER_CACHE_PATCH_SUCCESS')
            )
        );
        echo json_encode(array('status' => 'success'));
        jexit();
    }

    /*
     * Set the auto clean cache speedcache config to true
     */
    public function fixAutoClear()
    {
        $this->canAdmin();

        $this->setComponentParameter('clear_on_admin_tasks', '1');

        $this->cleanCaches();

        JFactory::getSession()->set(
            'speedache_message',
            array(
                'type' => 'success',
                'message' => JText::sprintf('COM_SPEEDCACHE_DASHBOARD_AUTO_CLEAR_PATCH_SUCCESS')
            )
        );
        echo json_encode(array('status' => 'success'));
        jexit();
    }

    /*
     * Set the browser cache speedcache config to false
     */
    public function fixExpiresHeaders()
    {
        $this->canAdmin();

        if (strpos($_SERVER['SERVER_SOFTWARE'], 'nginx') !== false) {
            JFactory::getSession()->set(
                'speedache_message',
                array(
                    'type' => 'error',
                    'message' => JText::sprintf('COM_SPEEDCACHE_DASHBOARD_EXPIRES_SERVER_ERROR')
                )
            );
            echo json_encode(array('status' => 'error'));
            jexit();
        }
        //Htaccess file doesn't exists
        if (!file_exists(JPATH_ROOT . DIRECTORY_SEPARATOR . '.htaccess')) {
            JFactory::getSession()->set(
                'speedache_message',
                array(
                    'type' => 'error',
                    'message' => JText::sprintf('COM_SPEEDCACHE_DASHBOARD_EXPIRES_PATCH_ERROR')
                )
            );
            echo json_encode(array('status' => 'error'));
            jexit();
        }

        //Open htaccess file and check if expires headers are already set
        $htaccessContent = file_get_contents(JPATH_ROOT . DIRECTORY_SEPARATOR . '.htaccess');
        if (strpos($htaccessContent, 'mod_expires') !== false ||
            strpos($htaccessContent, 'ExpiresActive') !== false ||
            strpos($htaccessContent, 'ExpiresDefault') !== false ||
            strpos($htaccessContent, 'ExpiresByType') !== false) {
            JFactory::getSession()->set(
                'speedache_message',
                array(
                    'type' => 'error',
                    'message' => JText::sprintf('COM_SPEEDCACHE_DASHBOARD_EXPIRES_PATCH_ERROR')
                )
            );
            echo json_encode(array('status' => 'error'));
            jexit();
        }

        $htaccessContent =
            "#Expires headers configuration added by SpeedCache component" . PHP_EOL .
            "<IfModule mod_expires.c>" . PHP_EOL .
            "   ExpiresActive On" . PHP_EOL .
            "   <FilesMatch \.(css|js|bmp|png|gif|jpe?g)$>" . PHP_EOL .
            "	  ExpiresDefault \"access plus 7 days\"" . PHP_EOL .
            "   </FilesMatch>" . PHP_EOL .
            "   ExpiresByType application/javascript \"access plus 7 days\"" . PHP_EOL .
            "   ExpiresByType text/javascript \"access plus 7 days\"" . PHP_EOL .
            "   ExpiresByType text/css \"access plus 7 days\"" . PHP_EOL .
            "   ExpiresByType image/jpeg \"access plus 7 days\"" . PHP_EOL .
            "   ExpiresByType image/png \"access plus 7 days\"" . PHP_EOL .
            "   ExpiresByType image/gif \"access plus 7 days\"" . PHP_EOL .
            "   ExpiresByType image/bmp \"access plus 7 days\"" . PHP_EOL .
            "</IfModule>" . PHP_EOL .
            "#End of expires headers configuration" . PHP_EOL .
            PHP_EOL .
            PHP_EOL .
            $htaccessContent;
        if (!file_put_contents(JPATH_ROOT . DIRECTORY_SEPARATOR . '.htaccess', $htaccessContent)) {
            JFactory::getSession()->set(
                'speedache_message',
                array(
                    'type' => 'error',
                    'message' => JText::sprintf('COM_SPEEDCACHE_DASHBOARD_EXPIRES_PATCH_ERROR')
                )
            );
            echo json_encode(array('status' => 'error'));
            jexit();
        }

        JFactory::getSession()->set(
            'speedache_message',
            array(
                'type' => 'success',
                'message' => JText::sprintf('COM_SPEEDCACHE_DASHBOARD_EXPIRES_PATCH_SUCCESS')
            )
        );
        echo json_encode(array('status' => 'success'));
        jexit();
    }


    /**
     * Check if current urser can administrate speedcache
     */
    protected function canAdmin()
    {
        $user = JFactory::getUser();
        if (!$user->authorise('core.admin', 'com_speedcache') || !JSession::checkToken('GET')) {
            echo json_encode(array('status' => 'error'));
            jexit();
        }
    }

    /**
     * Update a global config parameter
     * @param $param
     * @param $value
     */
    protected function setGlobalParameter($param, $value)
    {
        //Include class for saving into configuration file
        include_once(JPATH_ROOT . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_config' .
            DIRECTORY_SEPARATOR . 'model' . DIRECTORY_SEPARATOR . 'cms.php');
        include_once(JPATH_ROOT . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_config' .
            DIRECTORY_SEPARATOR . 'model' . DIRECTORY_SEPARATOR . 'form.php');
        include_once(JPATH_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_config' .
            DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . 'application.php');

        //Delete user state in case it contains datas
        JFactory::getApplication()->setUserState('com_config.config.global.data', null);

        $configModel = new ConfigModelApplication();

        //Load current datas
        $data = $configModel->getData();

        //Only change caching
        $data[$param] = $value;
        //Save back to configuration file
        $configModel->save($data);
    }

    /**
     * Update a speedcache main parameter
     * @param $param
     * @param $value
     */
    protected function setComponentParameter($param, $value)
    {
        $params = JComponentHelper::getParams('com_speedcache');

        $params->set($param, $value);

        $table = JTable::getInstance('extension');
        $table->load(JComponentHelper::getComponent('com_speedcache')->id);
        $table->bind(array('params' => $params->toString()));

        // check for error
        if (!$table->check()) {
            echo json_encode(array('status' => 'error'));
            jexit();
        }
        // Save to database
        if (!$table->store()) {
            echo json_encode(array('status' => 'error'));
            jexit();
        }
    }

    /**
     * Clean admin and front system cache
     */
    protected function cleanCaches()
    {
        //Clean user state
        JFactory::getApplication()->setUserState('com_config.config.global.data', null);
        JModelLegacy::addIncludePath(JPATH_ROOT . '/administrator/components/com_cache/models');
        $model = JModelLegacy::getInstance('cache', 'CacheModel');

        if (!empty($model)) {
            $clients = array(1, 0);
            foreach ($clients as $client) {
                $mCache = $model->getCache($client);
                foreach ($mCache->getAll() as $cache) {
                    $mCache->clean($cache->group);
                }
            }
        }
    }

    public function clearcache()
    {
        if (!JSession::checkToken('GET')) {
            echo json_encode(array('status' => 'error'));
            jexit();
        }
        $conf = JFactory::getConfig();
        //calculator front end cache size
        $fcache = $conf->get('cache_path', JPATH_SITE . '/cache');
        $fsize = self::getDirectSize($fcache);
        //calculator back end cache size
        $bcache = JPATH_ADMINISTRATOR . '/cache';
        $bsize = self::getDirectSize($bcache);
        //get total size of backend and frontend
        $totalSize = self::formatBytes($fsize + $bsize);
        //clear cache
        $this->cleanCaches();

        $params = JComponentHelper::getParams('com_speedcache');
        if ($params->get('cache_preloading_after_save')) {
            speedcacheHelper::processPreload();
        }


        echo json_encode(array('status' => 'success', 'size' => $totalSize));
        jexit();
    }

    /**
     * Transfer bytes
     * @param $bytes
     * @return string
     */
    public static function formatBytes($bytes)
    {
        if ($bytes >= 1073741824) {
            $bytes = number_format($bytes / 1073741824, 2);
        } elseif ($bytes >= 1048576) {
            $bytes = number_format($bytes / 1048576, 2);
        } elseif ($bytes >= 1024) {
            $bytes = number_format($bytes / 1024, 2);
        } elseif ($bytes > 1 || $bytes == 1) {
            //do nothing
        } else {
            $bytes = '0';
        }

        return $bytes;
    }

    /**
     * follow http://stackoverflow.com/questions/478121/php-get-directory-size
     * @param $path
     * @return int
     */
    public static function getDirectSize($path)
    {
        $bytestotal = 0;
        $path = realpath($path);
        if ($path !== false) {
            foreach (new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($path, FilesystemIterator::SKIP_DOTS)
            ) as $object) {
                $bytestotal += $object->getSize();
            }
        }
        return $bytestotal;
    }

    /**
     * Check module exist in apache
     * @param $module_name
     * @return bool
     */
    private function checkApacheModuleExists($module_name)
    {
        if (function_exists('apache_get_modules')) {
            $modules = apache_get_modules();
            return (in_array($module_name, $modules) ? true : false);
        } else {
            //Files to check
            $moduleOK = false;
            $filetypes = array(
                'png' => array('expires' => false, 'date' => false),
                'jpg' => array('expires' => false, 'date' => false),
                'gif' => array('expires' => false, 'date' => false),
                'css' => array('expires' => false, 'date' => false),
                'js' => array('expires' => false, 'date' => false)
            );
            foreach ($filetypes as $filetype => $value) {
                $ch = curl_init(JUri::root() . 'components/com_speedcache/assets/file.' . $filetype);
                curl_setopt($ch, CURLOPT_ENCODING, "gzip");
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_HEADER, true);
                $response = curl_exec($ch);
                $info = curl_getinfo($ch);
                $headers = substr($response, 0, $info['header_size']);

                foreach (explode("\r\n", $headers) as $header) {
                    $headerContent = explode(':', $header, 2);
                    if (trim($headerContent[0]) === 'Expires') {
                        if (!empty($headerContent[1])) {
                            $moduleOK = true;
                            break;
                        }
                    }
                }
            }
            return (($moduleOK) ? true : false);
        }
    }
}
