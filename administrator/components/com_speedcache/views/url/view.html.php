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
 * HTML View class for the speedcache component
 *
 * @since  1.6
 */
class speedcacheViewUrl extends JViewLegacy
{
    protected $form;

    protected $item;

    protected $state;

    /**
     * Execute and display a template script.
     *
     * @param   string $tpl The name of the template file to parse; automatically searches through the template paths.
     *
     * @return  mixed  A string if successful, otherwise a Error object.
     *
     * @since   1.6
     */
    public function display($tpl = null)
    {
        $this->form = $this->get('Form');
        $this->item = $this->get('Item');
        $this->state = $this->get('State');
        // Check for errors.
        if (count($errors = $this->get('Errors'))) {
            JError::raiseError(500, implode("\n", $errors));

            return false;
        }

        parent::display($tpl);
        $this->addToolbar();
    }

    /**
     * Add the page title and toolbar.
     *
     * @return  void
     *
     * @since   1.6
     */
    protected function addToolbar()
    {
        if ($this->getLayout() == 'edit') {
            JFactory::getApplication()->input->set('hidemainmenu', true);
            JToolbarHelper::title('SpeedCache : ' . JText::_('COM_SPEEDCACHE_EDIT_URL'));
            JToolbarHelper::apply('url.apply');
            JToolbarHelper::save('url.save');
            JToolbarHelper::cancel('url.cancel');
        }
        if ($this->getLayout() == 'addincluderulesitems') {
            JFactory::getApplication()->input->set('hidemainmenu', true);
            JToolbarHelper::title('SpeedCache : ' . JText::_('COM_SPEEDCACHE_EDIT_URL'));
            JToolbarHelper::apply('url.applyincludeRules');
            JToolbarHelper::save('url.saveincludeRules');
            JToolbarHelper::cancel('url.cancel');
        }
        if ($this->getLayout() == 'addexcludeitems') {
            JFactory::getApplication()->input->set('hidemainmenu', true);
            JToolbarHelper::title('SpeedCache : ' . JText::_('COM_SPEEDCACHE_EDIT_URL'));
            JToolbarHelper::apply('url.applyexclude');
            JToolbarHelper::save('url.saveexclude');
            JToolbarHelper::cancel('url.cancel');
        }
        if ($this->getLayout() == 'addexcluderulesitems') {
            JFactory::getApplication()->input->set('hidemainmenu', true);
            JToolbarHelper::title('SpeedCache : ' . JText::_('COM_SPEEDCACHE_EDIT_URL'));
            JToolbarHelper::apply('url.applyexcludeRules');
            JToolbarHelper::save('url.saveexcludeRules');
            JToolbarHelper::cancel('url.cancel');
        }
    }
}
