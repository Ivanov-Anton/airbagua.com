<?php
/**
 * @version    3.6.x
 * @package    Simple Image Gallery Pro
 * @author     JoomlaWorks - https://www.joomlaworks.net
 * @copyright  Copyright (c) 2006 - 2018 JoomlaWorks Ltd. All rights reserved.
 * @license    https://www.joomlaworks.net/license
 */

// no direct access
defined('_JEXEC') or die;

jimport('joomla.plugin.plugin');

class plgButtonJw_SigPro extends JPlugin
{
    public function __construct(&$subject, $config)
    {
        parent::__construct($subject, $config);
        $this->loadLanguage('', JPATH_ADMINISTRATOR);
    }

    public function onDisplay($name)
    {
        $document = JFactory::getDocument();
        if (version_compare(JVERSION, '3.0', 'lt')) {
            $document->addScript('https://cdn.jsdelivr.net/npm/jquery@1.12.4/dist/jquery.min.js');
        } else {
            JHtml::_('jquery.framework');
        }

        // Special treatment for Virtuemart
        if (JRequest::getCmd('option') == 'com_virtuemart') {
            $document->addScriptDeclaration("
				/* Simple Image Gallery Pro */
				(function($) {
				    window.SigProModal = function(el, link) {
				        var href = $(el).attr('href') || link;
				        $.fancybox({
				            type: 'iframe',
				            href: href,
		                    width: '96%',
		                    height: '96%'
				        });
				    }
				    window.SigProModalClose = function() {
				        $.fancybox.close();
				    }
				})(jQuery);
			");
        } else {
            // Fancybox
            $document->addStyleSheet('https://cdn.jsdelivr.net/npm/@fancyapps/fancybox@3.5.5/dist/jquery.fancybox.min.css');
            $document->addScript('https://cdn.jsdelivr.net/npm/@fancyapps/fancybox@3.5.5/dist/jquery.fancybox.min.js');
            $document->addScriptDeclaration("
				/* Simple Image Gallery Pro */
				(function($) {
				    window.SigProModal = function(el, link) {
				        var href = $(el).attr('href') || link;
				        $.fancybox.open({
				            type: 'iframe',
				            src: href,
				            toolbar: false,
				            smallBtn: true,
				            iframe: {
				                css: {
				                    width: '96%',
				                    height: '96%',
				                    //'max-width': '1440px',
				                    //'max-height': '1200px',
				                    margin: 0,
				                    padding: 0
				                }
				            }
				        });
				    }
				    window.SigProModalClose = function() {
				        $.fancybox.close();
				    }
				})(jQuery);
			");
        }

        $document->addStyleDeclaration('
        	/* Simple Image Gallery Pro */
			.sigProEditorButton {background:url('.JURI::root(true).'/media/jw_sigpro/assets/images/sigpro-icon-j15.png) 100% 0 no-repeat;}
			.icon-sigProEditorButton {background:url('.JURI::root(true).'/media/jw_sigpro/assets/images/sigpro-icon.png) 0 0 no-repeat !important;width:16px!important;height:16px!important;line-height:16px!important;position:relative!important;top:2px;}
        ');

        $button = new JObject();
        $link = 'index.php?option=com_sigpro&amp;tmpl=component&amp;type=site&amp;editorName='.$name;
        $j3xLink = 'index.php?option=com_sigpro&tmpl=component&type=site&editorName='.$name;
        $application = JFactory::getApplication();
        if ($application->isSite()) {
            $link .= '&amp;template=system';
            $j3xLink .= '&template=system';
        }
        $button->set('link', $link);
        $button->set('text', 'Simple Image Gallery Pro');
        $button->set('name', 'sigProEditorButton');
        $button->set('onclick', 'SigProModal(this); return false;');

        if (version_compare(JVERSION, '3.0', 'ge')) {
            $button->class = 'btn';
        }

        if (version_compare(JVERSION, '3.5', 'ge')) {
            $config = JFactory::getConfig();
            $user = JFactory::getUser();
            $editor = $user->getParam('editor', $config->get('editor'));
            if ($editor == 'tinymce') {
                $button->modal = false;
                $button->link = '#';
            }
            $button->set('onclick', 'SigProModal(this, \''.$j3xLink.'\'); return false;');
        }

        // Show when usable
        if (version_compare(JVERSION, '3.0', 'ge')) {
            if ($user->authorise('core.create', 'com_sigpro') === false) {
                return;
            }
        }

        return $button;
    }
}
