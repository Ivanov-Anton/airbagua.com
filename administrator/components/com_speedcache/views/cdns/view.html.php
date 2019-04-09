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
class speedcacheViewCdns extends JViewLegacy
{
    protected $cdn_active;

    protected $cdn_url;

    protected $cdn_content;

    protected $cdn_exclude_content;

    protected $cdn_relative_path;

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
        $params = JComponentHelper::getParams('com_speedcache');
        $this->cdn_active = $params->get('cdn_active', 0);
        $this->cdn_url = $params->get('cdn_url', '');
        $this->cdn_content = $params->get('cdn_content', '');
        $this->cdn_exclude_content = $params->get('cdn_exclude_content', '');
        $this->cdn_relative_path = $params->get('cdn_relative_path', 0);

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
        $canDo = JHelperContent::getActions('com_speedcache');
        JToolbarHelper::title('SpeedCache : ' . JText::_('COM_SPEEDCACHE_CONFIG_CDN_DESC'));

        if ($canDo->get('core.edit.state')) {
            JFactory::getApplication()->input->set('hidemainmenu', true);
            JToolbarHelper::apply('cdn.save');
        }

        if ($canDo->get('core.admin')) {
            JToolbarHelper::divider();
            JToolbarHelper::preferences('com_speedcache');
            JToolbarHelper::custom(
                'dashboard',
                'speedcachebackbtn',
                'speedcachebackbtn',
                JText::_('COM_SPEEDCACHE_BTN_BACK_DASHBOARD'),
                false
            );
        }
    }
}
