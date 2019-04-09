<?php
/**
 * Speedcache
 *
 * We developed this code with our hearts and passion.
 * We hope you found it useful, easy to understand and to customize.
 * Otherwise, please feel free to contact us at contact@imagerecycle.com *
 * @package Imagerecycle
 * @copyright Copyright (C) 2014 ImageRecycle (http://www.imagerecycle.com). All rights reserved.
 * @license GNU General Public License version 2 or later; http://www.gnu.org/licenses/gpl-2.0.html
 */


defined('_JEXEC') or die;


class speedcacheViewFolders extends JViewLegacy
{
    /**
     * Display the view
     */
    public function display($tpl = null)
    {
        JHtml::_('jquery.framework');
        $document = JFactory::getDocument();
        $document->addStyleSheet(JURI::root() . 'administrator/components/com_speedcache/assets/css/jaofiletree.css');
        $document->addScript(JURI::root() . 'administrator/components/com_speedcache/assets/js/jaofiletree.js');
        parent::display($tpl);
    }
}
