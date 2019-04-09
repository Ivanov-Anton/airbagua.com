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
class speedcacheModelDashboard extends JModelList
{
    /**
     * Count the active urls in database
     * @return int | false
     */
    public function countUrls()
    {
        $dbo = $this->getDbo();
        $query = 'SELECT COUNT(*) FROM #__speedcache_urls';
        $dbo->setQuery($query);
        return $dbo->loadResult();
    }

    /**
     * Check a least minify active
     * @return bool
     */
    public function checkMinifyOnDashBoard(){
        $dbo = $this->getDbo();
        $query = 'SELECT COUNT(*) FROM #__speedcache_minify_file WHERE minify=1';
        $dbo->setQuery($query);
        return $dbo->loadResult();
    }
}
