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
class speedcacheViewMenus extends JViewLegacy
{
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

        $model = $this->getModel('menus');
        $menuItems = $model->getItems();

        //Group items menu by menu and generate final url
        $this->menus = array();
        foreach ($menuItems as &$menuItem) {
            if (!isset($this->menus[$menuItem->menutype])) {
                $this->menus[$menuItem->menutype] = array();
            }

            $menuItem->params = new JRegistry($menuItem->params);

            //Instanciate a site router
            $config = JFactory::getConfig();
            $router = JRouter::getInstance('site');
            $router->setMode($config->get('sef', 1));

            //Generate the final link
            switch ($menuItem->type) {
                case 'separator':
                case 'heading':
                    // There is no link for this cases but we need to show them
                    break;

                case 'url':
                    if ((strpos($menuItem->link, 'index.php?') === 0) && (strpos($menuItem->link, 'Itemid=') === false)) {
                        // If this is an internal Joomla link, ensure the Itemid is set.
                        $menuItem->flink = $menuItem->link . '&Itemid=' . $menuItem->id;
                    } elseif (strpos($menuItem->link, 'http') !== 0) {
                        $menuItem->flink = $menuItem->link;
                    } else {
                        continue 2;
                    }
                    break;

                case 'alias':
                    $menuItem->flink = 'index.php?Itemid=' . $menuItem->params->get('aliasoptions');
                    break;

                default:
                    $menuItem->flink = 'index.php?Itemid=' . $menuItem->id;
                    break;
            }

            if (isset($menuItem->link) && $menuItem->link) {
                $uri = $router->build($menuItem->flink, true);
                $route = $uri->toString(array('path', 'query', 'fragment'));

                //Remove the administrator path
                $menuItem->flink = str_replace(JUri::base(true) . '/', '', $route);

                //Add the site uri
                $menuItem->flink = JUri::root() . $menuItem->flink;
            }

            //Affect this item to its menu
            $this->menus[$menuItem->menutype][] = $menuItem;
        }

        parent::display($tpl);

    }

}
