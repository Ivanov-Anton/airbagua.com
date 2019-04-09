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
class PlgInstallerSpeedcache_install extends JPlugin
{
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
    }

    public function onInstallerAfterInstaller()
    {
        //Enable installation plugin
        $db = JFactory::getDbo();
        $db->setQuery('UPDATE #__extensions SET `ordering`=0 WHERE element ="speedcache" AND type="plugin"');
        $db->execute();

        //Enable and set ordering for speedcache_last plugin
        $db->setQuery('UPDATE #__extensions SET `ordering`=2147483647 WHERE element ="speedcache_last" AND type="plugin"');
        $db->execute();
    }
}
