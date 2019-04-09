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
 * speedcache helper class.
 *
 * @since  1.6
 */
class speedcacheHelper
{
    protected static $actions;
    /**
     * Configure the Linkbar.
     *
     * @param   string $vName The name of the active view.
     *
     * @return  void
     *
     * @since   1.6
     */
    public static function addSubmenu($vName)
    {

    }

    /**
     * Gets a list of the actions that can be performed.
     *
     * @return  JObject
     *
     * @deprecated  3.2  Use JHelperContent::getActions() instead
     */
    public static function getActions()
    {
        // Log usage of deprecated function
//        JLog::add(__METHOD__ . '() is deprecated, use JHelperContent::getActions() with new arguments order instead.', JLog::WARNING, 'deprecated');
//
//        // Get list of actions
//        $result = JHelperContent::getActions('com_speedcache');
//        return $result;

        if (empty(self::$actions))
        {
            $user = JFactory::getUser();
            self::$actions = new JObject;

            $actions = JAccess::getActions('com_speedcache');

            foreach ($actions as $action)
            {
                self::$actions->set($action->name, $user->authorise($action->name, 'com_speedcache'));
            }
        }

        return self::$actions;
    }

    /**
     * Load global file language
     */
    public static function loadLanguage(){
        $lang = JFactory::getLanguage();
        $lang->load('com_speedcache',JPATH_ADMINISTRATOR.'/components/com_speedcache',null,true);
        $lang->load('com_speedcache.override',JPATH_ADMINISTRATOR.'/components/com_speedcache',null,true);
        $lang->load('com_speedcache.sys',JPATH_ADMINISTRATOR.'/components/com_speedcache',null,true);
    }
    /**
     * Get a list of filter options for the state of a module.
     *
     * @return  array  An array of JHtmlOption elements.
     *
     * @since   1.6
     */
    public static function getFileTypes()
    {
        // Build the filter options.
        $options = array();
        $options[] = JHtml::_('select.option', '0', 'FONT');
        $options[] = JHtml::_('select.option', '1', 'CSS');
        $options[] = JHtml::_('select.option', '2', 'JS');

        return $options;
    }

    //Do a HEAD request to the front component
    public static function processPreload()
    {
        $preloadingToken = '';
        if (function_exists('curl_exec')) {
            $params = JComponentHelper::getParams('com_speedcache');
            $preloadingToken = $params->get('preloading_token');
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, JUri::root() . 'index.php?option=com_speedcache&task=preload&token='.$preloadingToken);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($ch, CURLOPT_NOBODY, 0);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
            curl_exec($ch);
            curl_close($ch);
        }
    }
}
