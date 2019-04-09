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
 * speedcache list controller class.
 *
 * @since  1.6
 */
class speedcacheControllerConfigs extends JControllerAdmin
{
    private $allowed_ext = array('json');

    /**
     *
     */
    public function exportConfigurations()
    {
        //Check token
        if (!JSession::checkToken('GET')) {
            echo json_encode(array('status' => 'error'));
            jexit();
        }

        $cando = speedcacheHelper::getActions();

        if ($cando->get('core.admin')) {
            $table = JTable::getInstance('extension');
            $table->load(JComponentHelper::getComponent('com_speedcache')->id);
            $params = $table->params;

            if (is_string($params)) {
                $params .= '_key_'.md5('check_speedcache_json');
                echo json_encode(array('status' => 'success', 'params' =>$params));
                jexit();
            } else {
                echo json_encode(array('status' => 'error'));
                jexit();
            }
        }

        echo json_encode(array('status' => 'Permission denied'));
        jexit();
    }


    public function importConfigurations()
    {
        //Check token
        JSession::checkToken() or die( 'Invalid Token' );

        $cando = speedcacheHelper::getActions();

        if ($cando->get('core.admin')) {
            // Upload file
            if (isset($_FILES)) {
                $filetype = strtolower(pathinfo($_FILES['import_file']['name'], PATHINFO_EXTENSION));

                if (in_array($filetype, $this->allowed_ext)) {
                    // If it is json file, get content to update configuration
                    $fileContent = file_get_contents($_FILES['import_file']['tmp_name']);
                    $hash = md5('check_speedcache_json');
                    // Check token of speedcache configuration
                    if (!empty($fileContent)
                        && strpos($fileContent, '_key_'.$hash) !== false
                    ) {
                        $fileContent = str_replace('_key_'.$hash , '', $fileContent);

                        $table = JTable::getInstance('extension');
                        $table->load(JComponentHelper::getComponent('com_speedcache')->id);
                        $table->bind(array('params' => $fileContent));

                        // check for error
                        if (!$table->check()) {
                            echo json_encode(array('status' => 'Check error'));
                            jexit();
                        }
                        // Save to database
                        if (!$table->store()) {
                            echo json_encode(array('status' => 'Store configuration error'));
                            jexit();
                        }
                    } else {
                        jexit( 'Configuration Incorrect' );
                    }
                } else {
                    jexit( 'File type Incorrect !' );
                }
            }

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

            echo "<script>setTimeout(function(){ window.parent.location.reload(); }, 2000);</script>";

            jexit(JText::_('COM_SPEEDCACHE_SUCCESS_MESSAGE_AFTER_IMPORT'));
        }

        jexit( 'Permission denied !' );
    }



}

