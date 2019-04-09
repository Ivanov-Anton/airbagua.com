<?php
defined('_JEXEC') or die;

class SpeedCacheCleaner
{
    function __construct()
    {
        $lang = JFactory::getLanguage();
        $lang->load('com_speedcache',JPATH_ADMINISTRATOR.'/components/com_speedcache',null,true);
        $lang->load('com_speedcache.override',JPATH_ADMINISTRATOR.'/components/com_speedcache',null,true);
        $lang->load('com_speedcache.sys',JPATH_ADMINISTRATOR.'/components/com_speedcache',null,true);
    }

    function render()
    {
        $uri = JUri::getInstance();
        $url = $uri->toString();
        $script = "
			var sc_speedcache_base = '" . JUri::base(true) . "';
			var sc_speedcache_root = '" . JUri::root() . '/' . "';
			var sc_speedcache_msg = '" . JText::_('MOD_SPEEDCACHE_CLEAN_MSG') . "';
			var sc_speedcache_msg_error = '" . JText::_('MOD_SPEEDCACHE_CLEAN_MSG_ERROR') . "';
			var sc_token_key = '" . JSession::getFormToken() . "';
			";
        JFactory::getDocument()->addScriptDeclaration($script);

        JFactory::getDocument()->addScript(JUri::base() . 'modules/mod_speedcache/assets/mod_speedcache.js');
        JFactory::getDocument()->addStyleSheet(JUri::base() . 'modules/mod_speedcache/assets/mod_speedcache.css');

        $text = JText::_('MOD_SPEEDCACHE_TITLE');
        // Generate html for toolbar button
        $html = array();
        $html[] = '<a href="javascript:;" onclick="return false;"  class="btn btn-small sc_clearcache_link">';
        if (strpos($url, 'com_speedcache') !== false) {
            $html[] = '<img class="sc-image-loading" src="' . JUri::root() . 'administrator/modules/mod_speedcache/assets/images/loading-in.gif" />';
        } else {
            $html[] = '<img class="sc-image-loading" src="' . JUri::root() . 'administrator/modules/mod_speedcache/assets/images/loading-other.gif" />';
        }
        $html[] = '<span class="icon-purge icon-trash clear-cache-icon"></span> ';
        $html[] = $text;
        $html[] = '</a>';
        $toolbar = JToolBar::getInstance('toolbar');
        $toolbar->appendButton('Custom', implode('', $html));

        // Generate html for status link
        $html = array();
        $html[] = '<div class="btn-group sc_clearcache">';
        $html[] = '<span class="btn-group separator"></span>';
        $html[] = '<a href="javascript:;" onclick="return false;" class="sc_clearcache_link">';
        $html[] = '<img class="sc-image-loading" src="' . JUri::root() . 'administrator/modules/mod_speedcache/assets/images/loading-other.gif" />';
        $html[] = '<span class="icon-purge icon-trash clear-cache-icon"></span> ';
        $html[] = $text;
        $html[] = '</a>';
        $html[] = '</div>';

        echo implode('', $html);
    }
}
