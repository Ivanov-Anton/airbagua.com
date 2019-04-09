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
 * View class for a list of speedcache.
 *
 * @since  1.6
 */
class speedcacheViewUrls extends JViewLegacy
{
    protected $items;

    protected $cacheitems;

    protected $pagination;

    protected $state;

    protected $user;

    protected $listOrder;

    protected $listDirn;
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
        // Include the component HTML helpers.
        JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');
        JHtml::_('bootstrap.tooltip');
        JHtml::_('behavior.multiselect');
        JHtml::_('formbehavior.chosen', 'select');

        JHtml::_('jquery.framework');
        JHTML::_('behavior.modal');
        JFactory::getDocument()->addScript(JUri::base() . 'components/com_speedcache/assets/js/urls.js');

        $this->items = $this->get('Items');
        $this->cacheitems = $this->get('CacheItems');
        $this->pagination = $this->get('Pagination');
        $this->state = $this->get('State');

        $this->user = JFactory::getUser();
        $this->listOrder = $this->escape($this->state->get('list.ordering'));
        $this->listDirn = $this->escape($this->state->get('list.direction'));

        // Check for errors.
        if (count($errors = $this->get('Errors'))) {
            JError::raiseError(500, implode("\n", $errors));
            return false;
        }

        $this->addToolbar();

        parent::display($tpl);
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
        $state = $this->get('State');
        $canDo = JHelperContent::getActions('com_speedcache');
        JToolbarHelper::title(JText::_('COM_SPEEDCACHE_MANAGER'));
        $bar = JToolbar::getInstance('toolbar');

        if ($canDo->get('core.create')) {
            JToolbarHelper::addNew('url.add');

            $dhtml = "<button class='btn btn-small'>
			<span class='icon-speedcacheimportmenubtn'></span> " . JText::_('COM_SPEEDCACHE_BTN_ADD_FROM_MENU') . "</button>";

            $bar->appendButton('Custom', $dhtml, "speedcacheimportmenubtn");

            $bar->appendButton('Standard','addincludeRules',JText::_('COM_SPEEDCACHE_BTN_ADD_NEW'),'url.addincludeRulesItems',false);

            $bar->appendButton('Standard','addexclude',JText::_('COM_SPEEDCACHE_BTN_ADD_NEW'),'url.addexcludeItems',false);

            $bar->appendButton('Standard','addexcludeRules',JText::_('COM_SPEEDCACHE_BTN_ADD_NEW'),'url.addexcludeRulesItems',false);
        }

        if ($canDo->get('core.create')) {
            $dhtml = "<button class='btn btn-small'>
			<span class='icon-speedcacheselectmenubtn'></span> " . JText::_('COM_SPEEDCACHE_BTN_SELECT_FROM_MENU') . "</button>";

            $bar->appendButton('Custom', $dhtml, "speedcacheselectmenubtn");
        }

        if ($canDo->get('core.edit.state')) {
            JToolbarHelper::divider();

            //Select batch process type
            $dhtml = '<select name="action_state">
				<option value="guest">' . JText::_('COM_SPEEDCACHE_BULK_CHANGE_CACHE_GUEST') . '</option>
				<option value="preloadguest">' . JText::_('COM_SPEEDCACHE_BULK_CHANGE_PRELOAD_GUEST') . '</option>
				<option value="logged">' . JText::_('COM_SPEEDCACHE_BULK_CHANGE_CACHE_LOGGED') . '</option>
				<option value="preloadlogged">' . JText::_('COM_SPEEDCACHE_BULK_CHANGE_PRELOAD_LOGGED') . '</option>
				<option value="preloaduser">' . JText::_('COM_SPEEDCACHE_BULK_CHANGE_PRELOAD_USER') . '</option>
				<option value="excludeguest">' . JText::_('COM_SPEEDCACHE_BULK_CHANGE_EXCLUDE_GUEST') . '</option>
				<option value="excludelogged">' . JText::_('COM_SPEEDCACHE_BULK_CHANGE_EXCLUDE_LOGGED') . '</option>
			</select>';
            $bar->appendButton('Custom', $dhtml, "batch");

            $bar->appendButton('Standard', 'guest', 'Enable', 'urls.cacheGuest', true);
            $bar->appendButton('Standard', 'unguest', 'Disable', 'urls.uncacheGuest', true);

            $bar->appendButton('Standard', 'preloadguest', 'Enable', 'urls.preloadGuest', true);
            $bar->appendButton('Standard', 'unpreloadguest', 'Disable', 'urls.unpreloadGuest', true);

            $bar->appendButton('Standard', 'logged', 'Enable', 'urls.cacheLogged', true);
            $bar->appendButton('Standard', 'unlogged', 'Disable', 'urls.uncacheLogged', true);

            $bar->appendButton('Standard', 'preloadlogged', 'Enable', 'urls.preloadLogged', true);
            $bar->appendButton('Standard', 'unpreloadlogged', 'Disable', 'urls.unpreloadLogged', true);

            $bar->appendButton('Standard', 'preloaduser', 'Enable', 'urls.preloadperuser', true);
            $bar->appendButton('Standard', 'unpreloaduser', 'Disable', 'urls.unpreloadperuser', true);

            $bar->appendButton('Standard', 'excludeguest', 'Enable', 'urls.excludeGuest', true);
            $bar->appendButton('Standard', 'unexcludeguest', 'Disable', 'urls.unexcludeGuest', true);

            $bar->appendButton('Standard', 'excludelogged', 'Enable', 'urls.excludeLogged', true);
            $bar->appendButton('Standard', 'unexcludelogged', 'Disable', 'urls.unexcludeLogged', true);
        }

        JToolbarHelper::divider();

        if ($canDo->get('core.delete')) {
            JToolbarHelper::divider();
            JToolbarHelper::deleteList('JGLOBAL_CONFIRM_DELETE', 'urls.delete', 'JTOOLBAR_TRASH');
        }

        if ($canDo->get('core.admin')) {
            JToolbarHelper::divider();
            JToolbarHelper::preferences('com_speedcache');
            JToolbarHelper::custom('dashboard', 'speedcachebackbtn', 'speedcachebackbtn', JText::_('COM_SPEEDCACHE_BTN_BACK_DASHBOARD'), false);
        }
    }
}
