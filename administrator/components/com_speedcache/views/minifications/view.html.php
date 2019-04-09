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
class speedcacheViewMinifications extends JViewLegacy
{
    protected $items;

    protected $state;

    protected $groupCss = '0';

    protected $groupJS = '0';

    protected $groupFont = '0';

    protected $deferCSS = '0';

    protected $deferJS = '0';

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
        $this->items = $this->get('Items');
        $this->state = $this->get('State');
        $this->pagination = $this->get('Pagination');
        $params = JComponentHelper::getParams('com_speedcache');
        $this->groupCss = $params->get('minify_group_css', 0);
        $this->groupJS = $params->get('minify_group_js', 0);
        $this->groupFont = $params->get('minify_group_fonts', 0);
        $this->deferCSS = $params->get('defer_css', 0);
        $this->deferJS = $params->get('defer_js', 0);
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
        JToolbarHelper::title('SpeedCache : ' . JText::_('COM_SPEEDCACHE_MINIFICATIONS'));

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
