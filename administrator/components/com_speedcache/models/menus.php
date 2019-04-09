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
class speedcacheModelMenus extends JModelLegacy
{

    /*
     * Get a list of all menu items
     * @return Array of objects 
     */
    public function getItems()
    {
        $dbo = $this->getDbo();

        $query = 'SELECT m.*, mt.title as menu_title FROM #__menu AS m LEFT JOIN #__extensions AS e ON m.component_id = e.extension_id LEFT JOIN #__menu_types AS mt ON mt.menutype=m.menutype WHERE m.published = 1 AND m.parent_id > 0 AND m.client_id = 0 ORDER BY m.lft ASC';
        $dbo->setQuery($query);
        return $dbo->loadObjectList();
    }
}
