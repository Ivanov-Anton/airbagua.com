<?php /* ajaxmoduleloader file */
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
$jinput = JFactory::getApplication()->input;
$moduleposition = $jinput->get('modpos','position-7');

$module = JModuleHelper::getModule($moduleposition);
$contents = JModuleHelper::renderModule($module);
echo $contents
?>
