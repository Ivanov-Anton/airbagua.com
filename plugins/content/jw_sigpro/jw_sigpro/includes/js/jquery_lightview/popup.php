<?php
/**
 * @version    3.6.x
 * @package    Simple Image Gallery Pro
 * @author     JoomlaWorks - https://www.joomlaworks.net
 * @copyright  Copyright (c) 2006 - 2018 JoomlaWorks Ltd. All rights reserved.
 * @license    https://www.joomlaworks.net/license
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

/* Lightview v3.5.1 released on 07/09/2016 */
$relName = 'lightview';
$extraClass = 'lightview';
$customLinkAttributes = 'data-lightview-group="'.$gal_id.'"';

$stylesheets = array('css/lightview/lightview.css?v=3.5.1');
$stylesheetDeclarations = array();
$scripts = array(
    'js/spinners/spinners.min.js?v=3.5.1',
    'js/lightview/lightview.js?v=3.5.1'
);
$scriptDeclarations = array();

if (!defined('PE_LIGHTVIEW_LOADED')) {
    define('PE_LIGHTVIEW_LOADED', true);
    $legacyHeadIncludes = '<!--[if lt IE 9]><script type="text/javascript" src="'.$popupPath.'/js/excanvas/excanvas.js?v=3.5.1"></script><![endif]-->';
} else {
    $legacyHeadIncludes = '';
}
