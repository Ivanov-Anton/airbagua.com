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
class PlgSystemSpeedcache_last extends JPlugin
{

    public function onAfterRender()
    {
        JPluginHelper::importPlugin('system');
        $dispatcher = JEventDispatcher::getInstance();
        $dispatcher->trigger('speedcacheOnAfterRender');
    }

    /**
     * After render.
     *
     * @return   void
     *
     * @since   1.5
     */
    public function onAfterRespond()
    {
        JPluginHelper::importPlugin('system');
        $dispatcher = JEventDispatcher::getInstance();
        $dispatcher->trigger('speedcacheOnAfterRespond');
    }

    public function onContentCleanCache($options)
    {
        // Check clear cache when save content or menu item
        if ($options === 'com_menus' || $options === 'com_content') {
            JPluginHelper::importPlugin('system');
            $dispatcher = JEventDispatcher::getInstance();
            $dispatcher->trigger('speedcacheOnClearCache');
        }

    }

    public function onBeforeRender()
    {
        JPluginHelper::importPlugin('system');
        $dispatcher = JEventDispatcher::getInstance();
        $dispatcher->trigger('speedcacheOnBeforeRender');
    }
}
