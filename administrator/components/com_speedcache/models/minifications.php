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
class SpeedcacheModelMinifications extends JModelList
{
    /**
     * Constructor.
     *
     * @param   array $config An optional associative array of configuration settings.
     *
     * @see     JController
     * @since   1.6
     */
    public function __construct($config = array())
    {
        if (empty($config['filter_fields'])) {
            $config['filter_fields'] = array(
                'id', 'a.id',
                'file', 'a.file',
                'minify', 'a.minify',
                'type', 'a.type',
            );
        }

        parent::__construct($config);
    }

    /**
     * Method to auto-populate the model state.
     *
     * This method should only be called once per instantiation and is designed
     * to be called on the first call to the getState() method unless the model
     * configuration flag to ignore the request is set.
     *
     * Note. Calling getState in this method will result in recursion.
     *
     * @param   string $ordering An optional ordering field.
     * @param   string $direction An optional direction (asc|desc).
     *
     * @return  void
     *
     * @since   1.6
     */
    protected function populateState($ordering = null, $direction = null)
    {

        $state = $this->getUserStateFromRequest($this->context . '.filter.type', 'filter_type', '', 'string');
        $this->setState('filter.type', $state);
        // Load the filter state.
        $search = $this->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
        $this->setState('filter.search', $search);
        // List state information.
        parent::populateState('a.file', 'desc');
    }


    /**
     * @param string $type
     * @param string $prefix
     * @param array $config
     * @return bool|JTable
     */
    public function getTable($type = 'Minifications', $prefix = 'speedcacheTable', $config = array())
    {
        return JTable::getInstance($type, $prefix, $config);
    }

    /**
     * Get a list of all menu items
     * @return mixed
     */
    public function getListMenus()
    {
        $dbo = $this->getDbo();

        $query = 'SELECT m.*, mt.title AS menu_title FROM ';
        $query .= ' #__menu AS m LEFT JOIN #__extensions AS e ON m.component_id = e.extension_id ';
        $query .= ' LEFT JOIN #__menu_types AS mt ON mt.menutype=m.menutype ';
        $query .= ' WHERE m.published = 1 AND m.parent_id > 0 AND m.client_id = 0 ORDER BY m.lft ASC';
        $dbo->setQuery($query);
        return $dbo->loadObjectList();
    }


    /**
     * Build an SQL query to load the list data.
     *
     * @return  JDatabaseQuery
     *
     * @since   1.6
     */
    protected function getListQuery()
    {
        // Create a new query object.
        $db = $this->getDbo();
        $query = $db->getQuery(true);

        // Select the required fields from the table.
        $query->select(
            $this->getState(
                'list.select',
                'a.* '
            )
        );
        $query->from('#__speedcache_minify_file AS a');

        // Filter by search
        $search = $this->getState('filter.search');
        if (!empty($search)) {
            $search = $db->quote('%' . str_replace(' ', '%', $db->escape(trim($search), true) . '%'));
            $query->where('a.file LIKE ' . $search);
        }
        // Filter by published state.
        $state = $this->getState('filter.type');
        if (is_numeric($state)) {
            $query->where('a.type = ' . (int)$state);
        } elseif ($state === '') {
            $query->where('(a.type IN (0,1,2))');
        }
        $column = $db->escape($this->getState('list.ordering', 'a.file'));
        $column .= ' ' . $db->escape($this->getState('list.direction', 'DESC'));
        // Add the list ordering clause.
        $query->order($column);
        return $query;
    }

    /**
     * Return true in case an url already exist in database
     * @param $file
     * @return mixed
     */
    public function fileExists($file)
    {
        $dbo = $this->getDbo();
        $query = 'SELECT COUNT(*) FROM #__speedcache_minify_file WHERE file=' . $dbo->quote(trim($file));
        $dbo->setQuery($query);
        $dbo->execute();
        return $dbo->loadResult();
    }

    /**
     * Update cache per urer
     * @param $id
     * @param $val
     * @return bool
     */
    public function updateMinify($id, $val)
    {
        $db = $this->getDbo();
        $query = 'UPDATE #__speedcache_minify_file SET minify=';
        $query .= $db->quote((int)$val) . ' WHERE id=' . $db->quote((int)$id);
        $db->setQuery($query);
        $db->execute();
        return true;
    }

    /**
     * Change minify when select all
     * @param $ids
     * @param $state
     * @return bool
     */
    public function changeMinify($ids, $state)
    {
        $db = $this->getDbo();
        $query = 'UPDATE #__speedcache_minify_file SET minify=';
        $query .= $db->quote($state) . ' WHERE id IN(' . implode(',', $ids) . ')';
        $db->setQuery($query);
        $db->execute();
        return true;
    }

    /**
     * Get state of first minify
     * @param $id
     * @return mixed
     */
    public function getStateMinify($id)
    {
        $db = $this->getDbo();
        $query = 'SELECT `minify` FROM #__speedcache_minify_file WHERE id=' . $db->quote((int)$id);
        $db->setQuery($query);
        $db->execute();
        return $db->loadResult();
    }

    /**
     * Get id element activate
     * @return mixed
     */
    public function getMinifyItems()
    {
        $db = $this->getDbo();
        $query = "SELECT `id`,`file` FROM #__speedcache_minify_file WHERE minify = 1 ";
        $db->setQuery($query);
        $db->execute();
        return $db->loadAssocList();
    }

    /**
     * Remove minify by id
     * @param $ids
     * @return bool
     */
    public function deleteMinifyInID($ids)
    {
        $db = $this->getDbo();
        $query = 'DELETE FROM #__speedcache_minify_file WHERE id IN (' . implode(',', $ids) . ')';
        $db->setQuery($query);
        $db->execute();
        return true;
    }

    /**
     * Delete element by id
     * @return bool
     */
    public function deleteMinifyNotSet()
    {
        $db = $this->getDbo();
        $query = 'DELETE FROM #__speedcache_minify_file WHERE `minify` = 0';
        $db->setQuery($query);
        $db->execute();
        return true;
    }

    /**
     * save data to minify file table
     * @param $results
     * @return bool
     */
    public function saveMinifyFile($results)
    {
        if (empty($results)) {
            return false;
        }
        $db = $this->getDbo();

        $query = "INSERT IGNORE INTO `#__speedcache_minify_file` (file, minify, type ) VALUES ";
        $values = array();
        for ($i = 0; $i < count($results); $i++) {
            $val = '(' . $db->quote($results[$i]['file']) . ',' ;
            $val .= $db->quote($results[$i]['minify']) . ',' . $db->quote($results[$i]['type']) . ')';
            $values[] = $val;
        }
        $query .= implode(', ', $values);
        $query .= " ON DUPLICATE KEY UPDATE  `file` = VALUES(`file`) ";
        $db->setQuery($query);
        $db->execute();

        return true;
    }
}
