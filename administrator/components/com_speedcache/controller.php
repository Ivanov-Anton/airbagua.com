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
 * speedcache master display controller.
 *
 * @since  1.6
 */
class speedcacheController extends JControllerLegacy
{

    protected $default_view = 'dashboard';

    /**
     * Method to display a view.
     *
     * @param   boolean $cachable If true, the view output will be cached.
     * @param   array $urlparams An array of safe url parameters and their variable types, for valid values see {@link JFilterInput::clear()}.
     *
     * @return  JController        This object to support chaining.
     *
     * @since   1.5
     */
    public function display($cachable = false, $urlparams = false)
    {
        require_once JPATH_COMPONENT . '/helpers/speedcache.php';

        $view = $this->input->get('view', 'dashboard');
        $layout = $this->input->get('layout', 'default');
        $id = $this->input->getInt('id');

        JFactory::getApplication()->getDocument()->addStyleSheet(JUri::base() . 'components/com_speedcache/assets/css/main.css');
        JFactory::getApplication()->getDocument()->addStyleSheet('https://fonts.googleapis.com/icon?family=Material+Icons');

        // Check for edit form.
        if ($view == 'url' && $layout == 'edit' && !$this->checkEditId('com_speedcache.edit.url', $id)) {
            // Somehow the person just went to the form - we don't allow that.
            $this->setError(JText::sprintf('JLIB_APPLICATION_ERROR_UNHELD_ID', $id));
            $this->setMessage($this->getError(), 'error');
            $this->setRedirect(JRoute::_('index.php?option=com_speedcache&view=urls', false));

            return false;
        }

        // Load the submenu.
        speedcacheHelper::addSubmenu($this->input->get('view', 'urls'));
        parent::display();
    }

    public function clear()
    {
        $user = JFactory::getUser();
        if (!$user->authorise('core.admin', 'com_speedcache')) {
            $this->setError('Not authorized');
            $this->setMessage($this->getError(), 'error');
        } else {
            $conf = JFactory::getConfig();

            $options = array(
                'defaultgroup' => '',
                'storage' => $conf->get('cache_handler', ''),
                'caching' => true,
                'cachebase' => $conf->get('cache_path', JPATH_SITE . '/cache')
            );

            $cache = JCache::getInstance('', $options);
            $cache->clean();

        }

        JFactory::getSession()->set('speedache_message', array('type' => 'success', 'message' => JText::sprintf('COM_SPEEDCACHE_CACHE_CLEARED')));
        $return = JFactory::getApplication()->input->get('return', false);
        if ($return) {
            $this->setRedirect(base64_decode($return));
        } else {
            $this->setRedirect('index.php?option=com_speedcache&view=urls');
        }


    }

    public function dashboard()
    {
        $this->setRedirect('index.php?option=com_speedcache');
    }
}
