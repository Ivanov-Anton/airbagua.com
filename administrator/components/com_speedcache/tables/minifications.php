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
 * speedcache Table class
 *
 * @since  1.5
 */
class speedcacheTableMinifications extends JTable
{
    /**
     * Constructor
     *
     * @param   JDatabaseDriver &$db Database connector object
     *
     * @since   1.5
     */
    public function __construct(&$db)
    {
        parent::__construct('#__speedcache_minify_file', 'id', $db);
    }


}
