<?php
/**
 * JUUpdater
 *
 * We developed this code with our hearts and passion.
 * We hope you found it useful, easy to understand and to customize.
 * Otherwise, please feel free to contact us at contact@joomunited.com *
 * @package JUUpdater
 * @copyright Copyright (C) 2016 JoomUnited (https://www.joomunited.com). All rights reserved.
 * @license GNU General Public License version 2 or later; http://www.gnu.org/licenses/gpl-2.0.html
 */

defined('_JEXEC') or die;
//use Joomla\Registry\Registry;
jimport('joomla.plugin.plugin');

/**
 * Juupdater Installer plugin
 *
 * @since  1.5
 */
class PlgInstallerJuupdater extends JPlugin
{
    function onInstallerBeforePackageDownload($url, $headers)
    {
        if (strpos($url, 'infosite=joomunited')) {
            $url_checktoken = str_replace('task=download.download', 'task=download.checktoken', $url);
            $app = JFactory::getApplication();

            $http = JHttpFactory::getHttp();
            $response = $http->get($url_checktoken);
            $res_body = json_decode($response->body);
            if ($res_body->status == false) {
                if ($res_body->linkdownload != '') {
                    JError::raiseError('', $res_body->linkdownload);
                } else {
                    JError::raiseError('', $res_body->datas);
                }
                $app->redirect(JUri::base() . 'index.php?option=com_installer&view=update');
            }
        }
    }
}
