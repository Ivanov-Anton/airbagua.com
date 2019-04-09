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

if (!JFactory::getUser()->authorise('core.manage', 'com_speedcache')) {
    return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
}

require_once JPATH_COMPONENT . '/helpers/speedcache.php';
// Register helper class
JLoader::register('speedcacheComponentHelper', JPATH_ADMINISTRATOR . '/components/com_speedcache/helpers/component.php');

speedcacheHelper::loadLanguage();

$task = JFactory::getApplication()->input->get('task');
$controller = JControllerLegacy::getInstance('speedcache');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();
