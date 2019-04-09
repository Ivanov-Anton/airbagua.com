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
class speedcacheTableUrl extends JTable
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
        parent::__construct('#__speedcache_urls', 'id', $db);
    }

    /**
     * Validation and filtering.
     *
     * @return  boolean
     *
     * @since   1.5
     */
    public function check()
    {
        if (empty($this->url)) {
            $this->setError(JText::_('COM_SPEEDCACHE_ERROR_INVALID_SUBJECT'));

            return false;
        }
        $this->url = trim($this->url, '/');

        return true;
    }

    public function publish($pks = null, $state = 1, $userId = 0)
    {
        $this->setColumnAlias('published', 'state');
        return parent::publish($pks, $state, $userId);
    }
}
