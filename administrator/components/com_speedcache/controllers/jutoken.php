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

// no direct access
defined('_JEXEC') or die;
jimport('joomla.filesystem.folder');

JLoader::register('JuupdaterHelper', JPATH_SITE . '/plugins/installer/juupdater/helper.php');

class SpeedcacheControllerJutoken extends JControllerForm
{

    public function ju_add_token()
    {
        JuupdaterHelper::ju_add_token();
    }

    public function ju_remove_token()
    {
        JuupdaterHelper::ju_remove_token();
    }

    private function exit_status($status, $datas = array())
    {
        JuupdaterHelper::exit_status($status, $datas = array());
    }

    function check_config_token()
    {
        return JuupdaterHelper::check_config_token();
    }

    function ju_update_config_token($token)
    {
        JuupdaterHelper::ju_update_config_token($token);
    }

    function ju_update_site_token($token)
    {
        JuupdaterHelper::ju_update_site_token($token);
    }
}