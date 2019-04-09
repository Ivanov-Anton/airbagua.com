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
class speedcacheModelUrls extends JModelList
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
                'url', 'a.url',
                'cacheguest', 'a.cacheguest',
                'cachelogged', 'a.cachelogged',
                'preloadguest', 'a.preloadguest',
                'preloadlogged', 'a.preloadlogged',
                'preloadperuser', 'a.preloadperuser',
                'excludeguest','a.excludeguest',
                'excludelogged','a.excludelogged',
                'ignoreparams','a.ignoreparams'
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
        // Load the filter state.
        $search = $this->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
        $this->setState('filter.search', $search);

        // List state information.
        parent::populateState('a.url', 'desc');
    }

    /**
     * Method to get a store id based on model configuration state.
     *
     * This is necessary because the model is used by the component and
     * different modules that might need different sets of data or different
     * ordering requirements.
     *
     * @param   string $id A prefix for the store id.
     *
     * @return  string    A store id.
     *
     * @since   1.6
     */
    protected function getStoreId($id = '')
    {
        // Compile the store id.
        $id .= ':' . $this->getState('filter.search');
        return parent::getStoreId($id);
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
        $query->from('#__speedcache_urls AS a');

        // Filter by search in subject or speedcache.
        $search = $this->getState('filter.search');

        if (!empty($search)) {
            $search = $db->quote('%' . str_replace(' ', '%', $db->escape(trim($search), true) . '%'));
            $query->where('a.url LIKE ' . $search);
        }
        //filter by exclude , rules_include , rules_exclude
        $query->where('a.type = ' . $db->quote('include'));
        // Add the list ordering clause.
        $query->order($db->escape($this->getState('list.ordering', 'a.url')) . ' ' . $db->escape($this->getState('list.direction', 'DESC')));
        return $query;
    }

    /*
     * return list include url from database
     */
    public function getCacheItems()
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
        $query->from('#__speedcache_urls AS a');

        // Filter by search in subject or speedcache.
        $search = $this->getState('filter.search');

        if (!empty($search)) {
            $search = $db->quote('%' . str_replace(' ', '%', $db->escape(trim($search), true) . '%'));
            $query->where('a.url LIKE ' . $search);
        }
        //filter by exclude , rules_include , rules_exclude
        $query->where('a.type !=' . $db->quote('include'));
        // Add the list ordering clause.
        $query->order($db->escape($this->getState('list.ordering', 'a.url')) . ' ' . $db->escape($this->getState('list.direction', 'DESC')));
        try {
            // Load the list items and add the items to the internal cache.
            $db->setQuery($query);
            $result = $db->loadObjectList();
        } catch (RuntimeException $e) {
            $this->setError($e->getMessage());

            return false;
        }
        return $result;
    }

}
