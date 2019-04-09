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

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

class pkg_speedcacheInstallerScript
{
    protected $sc_oldRelease = null;

    public function __construct()
    {
        $this->sc_oldRelease = $this->sc_get_version('com_speedcache');
    }

    public function preflight($type, $parent)
    {
        // Check the Joomla! version to install
        if (version_compare(JVERSION, '3.7.0', 'lt'))
        {
            $msg = "<p>You're trying to install the component on a old an unsupported Joomla version.
 Please update your Joomla core first to get the latest Joomla security patches and features.
  You will then be able to install the component.</p>";
            JLog::add($msg, JLog::WARNING, 'jerror');

            return false;
        }

        return true;
    }

    function update($parent)
    {
        $dbo = JFactory::getDbo();
        if (version_compare($this->sc_oldRelease, '1.0.3', 'le')) {
            //update url , not store the site base url
            $query = 'UPDATE `#__speedcache_urls` SET `url`= SUBSTRING(url, LENGTH("'.JUri::root().'/"))' ;
            $dbo->setQuery($query);
            if (!$dbo->query()) {
            }
            //update paramter for column
            $query = 'UPDATE `#__speedcache_urls` SET `guest`=1 ,`logged`=1 WHERE `state`=1' ;
            $dbo->setQuery($query);
            if (!$dbo->query()) {
            }

            $query = 'UPDATE `#__speedcache_urls` SET `guest`=0 , `logged`=0 WHERE `state`=0' ;
            $dbo->setQuery($query);
            if (!$dbo->query()) {
            }

            //rename guest,logged column
            $query = 'ALTER TABLE `#__speedcache_urls` CHANGE `guest` `cacheguest` tinyint(1)';
            $dbo->setQuery($query);
            if (!$dbo->query()) {
            }
            $query = 'ALTER TABLE `#__speedcache_urls` CHANGE `logged` `cachelogged` tinyint(1)';
            $dbo->setQuery($query);
            if (!$dbo->query()) {
            }
            //add new paramerter
            $query = 'ALTER TABLE `#__speedcache_urls` ADD COLUMN `preloadguest` tinyint(1) NOT NULL AFTER  `cachelogged`,
                      ADD COLUMN `preloadlogged` tinyint(1) NOT NULL AFTER  `preloadguest`,
                      ADD COLUMN `preloadperuser` tinyint(1) NOT NULL AFTER  `preloadlogged`,
                      ADD COLUMN `excludeguest` tinyint(1) NOT NULL AFTER  `preloadperuser`,
                      ADD COLUMN `excludelogged` tinyint(1) NOT NULL AFTER  `excludeguest`,
                      ADD COLUMN `type` varchar(50) NOT NULL AFTER  `excludelogged`';
            $dbo->setQuery($query);
            if (!$dbo->query()) {
            }
            //update paramter for column
            $query = 'UPDATE `#__speedcache_urls` SET `type`=' . $dbo->quote('include') . ' WHERE `state`=1 OR `state`=0';
            $dbo->setQuery($query);
            if (!$dbo->query()) {
            }
            //drop state column
            $query = 'ALTER TABLE `#__speedcache_urls` DROP COLUMN `state`';
            $dbo->setQuery($query);
            if (!$dbo->query()) {
            }
            //create table minify
            $query = 'CREATE TABLE IF NOT EXISTS `#__speedcache_minify_file` (
                            `id` int(11) NOT NULL AUTO_INCREMENT,
                            `file` varchar(255) NOT NULL UNIQUE,
                            `minify` tinyint(2) NOT NULL,
                            `type` tinyint(3) NOT NULL,
                            PRIMARY KEY (`id`)
                        ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;';
            $dbo->setQuery($query);
            if (!$dbo->query()) {
            }

            //set module
            //Set the system plugin remember before speedcache
            $dbo->setQuery('UPDATE #__extensions SET enabled=1 WHERE element ="mod_speedcache" AND type="module"');
            if (!$dbo->query()) {
            }

            //Set the system plugin remember before speedcache
            $dbo->setQuery('UPDATE #__extensions SET enabled=1 WHERE element ="speedcache_content" AND type="plugin"');
            if (!$dbo->query()) {
            }

            $params = '{"module_tag":"div","bootstrap_size":"0","header_tag":"h3","header_class":"","style":"0"}';
            //Set position of module
            $dbo->setQuery('UPDATE #__modules SET position="status",`ordering`=3,published=1,params=' . $dbo->quote($params) . ' WHERE module ="mod_speedcache" ');
            if (!$dbo->query()) {
            }

            $query = 'SELECT id FROM #__modules WHERE module=' . $dbo->quote('mod_speedcache');
            $dbo->setQuery($query);
            $mid = $dbo->loadResult();

            $query = 'INSERT INTO #__modules_menu (`moduleid`,`menuid`) VALUES (' . $mid . ', 0 ) ON DUPLICATE KEY UPDATE `menuid` = 0';
            $dbo->setQuery($query);
            if (!$dbo->query()) {
            }
        }

        if (version_compare($this->sc_oldRelease, '2.0.1', 'le')) {
            //delete all record
            $query = 'DELETE FROM `#__speedcache_minify_file`';
            $dbo->setQuery($query);
            if (!$dbo->query()) {
            }
            //add unique key
            $query = 'ALTER TABLE `#__speedcache_minify_file` ADD UNIQUE(`file`)';
            $dbo->setQuery($query);
            if (!$dbo->query()) {
            }

            //add new paramerter
            $query = 'ALTER TABLE `#__speedcache_urls` ADD COLUMN `lifetime` tinyint(1) NOT NULL AFTER  `preloadperuser`,
                      ADD COLUMN `specifictime` int(10) NOT NULL AFTER  `lifetime`';
            $dbo->setQuery($query);
            if (!$dbo->query()) {
            }

            //update paramter for column
            $query = 'UPDATE `#__speedcache_urls` SET `lifetime`= 1,`specifictime`= 0 WHERE `type`= "include"';
            $dbo->setQuery($query);
            if (!$dbo->query()) {
            }
        }

        if (version_compare($this->sc_oldRelease, '2.0.4', 'le')) {
            //update paramter for lifetime column
            $query = 'UPDATE `#__speedcache_urls` SET `lifetime`= 1,`specifictime`= 0 WHERE `type`= "rules_include" AND `lifetime`= 0';
            $dbo->setQuery($query);
            if (!$dbo->query()) {
            }
        }

        if (version_compare($this->sc_oldRelease, '2.5.1', 'le')) {
            //update paramter for ingoreparams column
            $query = 'ALTER TABLE `#__speedcache_urls` ADD COLUMN `ignoreparams` tinyint(1) NOT NULL AFTER  `specifictime`';
            $dbo->setQuery($query);
            if (!$dbo->query()) {
            }

            //update paramter for column
            $query = 'UPDATE `#__speedcache_urls` SET `ignoreparams`= 0 WHERE `type`= "rules_include"';
            $dbo->setQuery($query);
            if (!$dbo->query()) {
            }
        }
    }

    /**
     * method to run after an install/update/uninstall method
     *
     * @return void
     */

    function postflight($type, $parent)
    {
        //Create file ajax load module
        $tmpl_path = JPATH_ROOT.'/templates/system/speedcacheajaxloadmodule.php';
        $source_path = JPATH_ROOT . '/plugins/system/speedcache/ajax_load_modules/speedcacheajaxloadmodule.php';
        JFile::copy($source_path,$tmpl_path);

        if ($type === 'install') {
            //Enable and set ordering for speedcache plugin
            $db = JFactory::getDbo();
            $db->setQuery('UPDATE #__extensions SET enabled=1, `ordering`=0 WHERE element ="speedcache" AND type="plugin"');
            $db->execute();

            //Enable and set ordering for speedcache_last plugin
            $db->setQuery('UPDATE #__extensions SET enabled=1, `ordering`=2147483647 WHERE element ="speedcache_last" AND type="plugin"');
            $db->execute();

            //Enable installation plugin
            $db->setQuery('UPDATE #__extensions SET enabled=1 WHERE element ="speedcache_install" AND type="plugin"');
            $db->execute();

            //Set the system plugin remember before speedcache
            $db->setQuery('UPDATE #__extensions SET enabled=1, `ordering`=-1 WHERE element ="remember" AND type="plugin"');
            $db->execute();

            //Set the system plugin remember before speedcache
            $db->setQuery('UPDATE #__extensions SET enabled=1 WHERE element ="mod_speedcache" AND type="module"');
            $db->execute();

            //Set the system plugin remember before speedcache
            $db->setQuery('UPDATE #__extensions SET enabled=1 WHERE element ="speedcache_content" AND type="plugin"');
            $db->execute();

            $params = '{"module_tag":"div","bootstrap_size":"0","header_tag":"h3","header_class":"","style":"0"}';
            //Set position of module
            $db->setQuery('UPDATE #__modules SET position="status",`ordering`=3,published=1,params=' . $db->quote($params) . ' WHERE module ="mod_speedcache" ');
            $db->execute();

            $query = 'SELECT id FROM #__modules WHERE module=' . $db->quote('mod_speedcache');
            $db->setQuery($query);
            $mid = $db->loadResult();

            $query = 'INSERT INTO #__modules_menu (`moduleid`,`menuid`) VALUES (' . $mid . ', 0 ) ON DUPLICATE KEY UPDATE `menuid` = 0';
            $db->setQuery($query);
            $db->execute();

            //Create token
            $component = JComponentHelper::getComponent('com_speedcache');

            $data = array('params' => array('preloading_token' => md5(mt_rand())));

            $table = JTable::getInstance('extension');
            // Load the previous Data
            if (!$table->load($component->id)) {
                return false;
            }
            // Bind the data.
            if (!$table->bind($data)) {
                return false;
            }

            // Check the data.
            if (!$table->check()) {
                return false;
            }

            // Store the data.
            if (!$table->store()) {
                return false;
            }

        }


        if ($type === 'install' || $type == 'update') {
            //Update joomunited token if already exists
            $dbo = JFactory::getDbo();
            $tables = $dbo->getTableList();
            $app = JFactory::getApplication();
            $prefix = $app->getCfg('dbprefix');
            if (in_array($prefix . 'joomunited_config', $tables)) {
                $query = $dbo->getQuery(true);
                $query->select('*');
                $query->from('#__joomunited_config');
                $dbo->setQuery($query);

                $results = $dbo->loadObject();
                if (!empty($results)) {
                    $token = $results->value;
                    if (!empty($token)) {
                        $token = str_replace('token=', '', $token);
                        $com_name = $parent->get('element');
                        $script = '<script type="text/javascript">';
                        $script .= 'jQuery(document).ready(function($){';
                        $script .= "jQuery.ajax({
                                                url     :   'index.php?option=$com_name&task=jutoken.ju_add_token',
                                                method    : 'GET',
                                                dataType : 'json',
                                                data    :   {
                                                    'token': '$token',
                                                }
                                            }).done(function(response){

                                            });";
                        $script .= '});';
                        $script .= '</script>';
                        echo $script;
                    }
                }
            }

            $imgsrc = JURI::root() . '/administrator/components/com_speedcache/assets/images/check.png';
            //Echo after install html
            echo '<p style="font-family: Open Sans,Helvetica,Arial,sans-serif;">
                <h2><strong>Speed Cache extension </strong></h2><br /></p>
                
                <p style="font-family: Open Sans,Helvetica,Arial,sans-serif;"><a style="text-decoration: none; background-color: #2089c0; color: #fff; padding: 10px 20px 10px 20px; border-radius 2px;" href="index.php?option=com_speedcache">LOAD THE DASHBOARD</a> </p>
                
                <p style="font-family: Open Sans,Helvetica,Arial,sans-serif;">
                    <br /><br /> Speed Cache extension features:
                    <ul>
                        <li style="background-image: url(' . $imgsrc . '); background-repeat: no-repeat; background-position: left center; padding-left: 24px;list-style-type: none; line-height: 25px; font-family: Open Sans,Helvetica,Arial,sans-serif;">Generates a 2nd cache level with more static content</li>
                        <li style="background-image: url(' . $imgsrc . '); background-repeat: no-repeat; background-position: left center; padding-left: 24px;list-style-type: none; line-height: 25px; font-family: Open Sans,Helvetica,Arial,sans-serif;">Activate a full browser caching</li>
                        <li style="background-image: url(' . $imgsrc . '); background-repeat: no-repeat; background-position: left center; padding-left: 24px;list-style-type: none; line-height: 25px; font-family: Open Sans,Helvetica,Arial,sans-serif;">URL & Menu inclusion/exclusion for page caching</li>
                         <li style="background-image: url(' . $imgsrc . '); background-repeat: no-repeat; background-position: left center; padding-left: 24px;list-style-type: none; line-height: 25px; font-family: Open Sans,Helvetica,Arial,sans-serif;">Option to include/exclude logged in users</li>
                        <li style="background-image: url(' . $imgsrc . '); background-repeat: no-repeat; background-position: left center; padding-left: 24px;list-style-type: none; line-height: 25px; font-family: Open Sans,Helvetica,Arial,sans-serif;">Generate cache files per user for dynamic content</li>
                         <li style="background-image: url(' . $imgsrc . '); background-repeat: no-repeat; background-position: left center; padding-left: 24px;list-style-type: none; line-height: 25px; font-family: Open Sans,Helvetica,Arial,sans-serif;">Cache automatic preloading</li>
                        <li style="background-image: url(' . $imgsrc . '); background-repeat: no-repeat; background-position: left center; padding-left: 24px;list-style-type: none; line-height: 25px; font-family: Open Sans,Helvetica,Arial,sans-serif;">Add Header expiration control</li>
                         <li style="background-image: url(' . $imgsrc . '); background-repeat: no-repeat; background-position: left center; padding-left: 24px;list-style-type: none; line-height: 25px; font-family: Open Sans,Helvetica,Arial,sans-serif;">One clic cache cleaning: default cache, 2nd level static cache, browser cache, user cache</li>
                       <li style="background-image: url(' . $imgsrc . '); background-repeat: no-repeat; background-position: left center; padding-left: 24px;list-style-type: none; line-height: 25px; font-family: Open Sans,Helvetica,Arial,sans-serif;">Automatic cache cleaner based on actions on frontend and backend</li>
                        <li style="background-image: url(' . $imgsrc . '); background-repeat: no-repeat; background-position: left center; padding-left: 24px;list-style-type: none; line-height: 25px; font-family: Open Sans,Helvetica,Arial,sans-serif;">Dashboard with one click performance enhancement</li>
                    </ul>
                </p>';
        }
        //Clean backend cache
        $options = array(
            'defaultgroup' => '',
            'cachebase' => JPATH_ADMINISTRATOR . '/cache'
        );
        $cache = JCache::getInstance('callback', $options);
        $cache->clean();

        return true;
    }

    /**
     * Method to get the version of a component
     * @param string $option
     * @return null
     */
    private function sc_get_version($option)
    {
        $manifest = self::sc_getManifest($option);

        if (property_exists($manifest, 'version')) {
            return $manifest->version;
        }
        return null;
    }

    /**
     * Method to get an object containing the manifest values
     * @param string $option
     * @return object
     */
    private function sc_getManifest($option)
    {
//                $component = JComponentHelper::getComponent($option);
        $dbo = JFactory::getDbo();
        $query = 'SELECT extension_id FROM #__extensions WHERE element=' . $dbo->quote($option) . ' AND type="component"';
        if (!$dbo->setQuery($query)) {
            return false;
        }
        if (!$dbo->query()) {
            return false;
        }
        $component = $dbo->loadResult();

        if (!$component) {
            return false;
        }
        $table = JTable::getInstance('extension');
        // Load the previous Data
        if (!$table->load($component, false)) {
            return false;
        }
        return json_decode($table->manifest_cache);
    }
}