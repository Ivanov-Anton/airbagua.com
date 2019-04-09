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
 * Private speedcache model.
 *
 * @since  1.6
 */
class speedcacheModelUrl extends JModelAdmin
{
    /**
     * speedcache
     */
    protected $item;

    protected $_tbl_key = 'id';

    protected $_tbl = '#__speedcache_urls';

    protected $associationsContext = 'com_speedcache.url';

    /**
     * Returns a Table object, always creating it.
     *
     * @param   type $type The table type to instantiate
     * @param   string $prefix A prefix for the table class name. Optional.
     * @param   array $config Configuration array for model. Optional.
     *
     * @return  JTable  A database object
     *
     * @since   1.6
     */
    public function getTable($type = 'Url', $prefix = 'speedcacheTable', $config = array())
    {
        return JTable::getInstance($type, $prefix, $config);
    }


    /**
     * Method to get the record form.
     *
     * @param   array $data Data for the form.
     * @param   boolean $loadData True if the form is to load its own data (default case), false if not.
     *
     * @return  JForm   A JForm object on success, false on failure
     *
     * @since   1.6
     */
    public function getForm($data = array(), $loadData = true)
    {
        // Get the form.
        $form = $this->loadForm('com_speedcache.url', 'url', array('control' => 'jform', 'load_data' => $loadData));

        if (empty($form)) {
            return false;
        }
        $params = JComponentHelper::getParams('com_speedcache');

        if ($params->get('cacheguest') !== null) {
            $form->setFieldAttribute('cacheguest', 'default', $params->get('cacheguest'));
        }
        if ($params->get('preloadguest') !== null) {
            $form->setFieldAttribute('preloadguest', 'default', $params->get('preloadguest'));
        }
        if ($params->get('cachelogged') !== null) {
            $form->setFieldAttribute('cachelogged', 'default', $params->get('cachelogged'));
        }
        if ($params->get('preloadlogged') !== null) {
            $form->setFieldAttribute('preloadlogged', 'default', $params->get('preloadlogged'));
        }
        if ($params->get('preloadperuser') !== null) {
            $form->setFieldAttribute('preloadperuser', 'default', $params->get('preloadperuser'));
        }
        if ($params->get('excludeguest') !== null) {
            $form->setFieldAttribute('excludeguest', 'default', $params->get('excludeguest'));
        }
        if ($params->get('excludelogged') !== null) {
            $form->setFieldAttribute('excludelogged', 'default', $params->get('excludelogged'));
        }
        return $form;
    }

    /**
     * Method to get the data that should be injected in the form.
     *
     * @return  mixed  The data for the form.
     *
     * @since   1.6
     */
    protected function loadFormData()
    {
        // Check the session for previously entered form data.
        $data = JFactory::getApplication()->getUserState('com_speedcache.edit.url.data', array());

        if (empty($data)) {
            $data = $this->getItem();
        }

        $this->preprocessData('com_speedcache.url', $data);

        return $data;
    }

    public function changeState($pks = null, $state = 1, $column = 'state')
    {
        $k = $this->_tbl_key;

        // Sanitize input.
        JArrayHelper::toInteger($pks);
        $state = (int)$state;

        // If there are no primary keys set check to see if the instance key is set.
        if (empty($pks)) {
            if ($this->$k) {
                $pks = array($this->$k);
            } // Nothing to set publishing state on, return false.
            else {
                $this->setError(JText::_('JLIB_DATABASE_ERROR_NO_ROWS_SELECTED'));

                return false;
            }
        }

        // Build the WHERE clause for the primary keys.
        $where = $k . ' IN (' . implode(',', $pks) . ')';

        // Update the publishing state for rows with the given primary keys.
        $this->_db->setQuery(
            'UPDATE ' . $this->_db->quoteName($this->_tbl)
            . ' SET ' . $this->_db->quoteName($column) . ' = ' . (int)$state
            . ' WHERE (' . $where . ')'
        );

        try {
            $this->_db->execute();
        } catch (RuntimeException $e) {
            $this->setError($e->getMessage());

            return false;
        }
        // If the JTable instance value is in the list of primary keys that were set, set the instance.
        if (!empty($this->$k)) {
            if (in_array($this->$k, $pks)) {
                $this->state = $state;
            }
        }

        return true;
    }

    /*
     * Return true in case an url already exist in database
     */
    public function urlExists($url)
    {
        $dbo = $this->getDbo();
        $query = 'SELECT COUNT(*) FROM #__speedcache_urls WHERE url=' . $dbo->quote(trim($url));
        $dbo->setQuery($query);
        $dbo->execute();
        return $dbo->loadResult();
    }

    //get cache item from id
    public function getCacheValues($id,$cachename){
        $dbo = $this->getDbo();
        $query = 'SELECT '.$cachename.' FROM #__speedcache_urls WHERE id=' . (int)($id);
        $dbo->setQuery($query);
        $dbo->execute();
        return $dbo->loadResult();
    }
}
