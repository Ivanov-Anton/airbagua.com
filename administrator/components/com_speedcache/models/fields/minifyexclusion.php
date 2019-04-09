<?php
/**
 * Speedcache
 *
 * We developed this code with our hearts and passion.
 * We hope you found it useful, easy to understand and to customize.
 * Otherwise, please feel free to contact us at contact@joomunited.com *
 * @package Speedcache
 * @copyright Copyright (C) 2013 JoomUnited (http://www.joomunited.com). All rights reserved.
 * @copyright Copyright (C) 2013 Damien Barr?re (http://www.crac-design.com). All rights reserved.
 * @license GNU General Public License version 2 or later; http://www.gnu.org/licenses/gpl-2.0.html
 */

defined('_JEXEC') or die;

jimport('joomla.form.formfield');

/**
 * Form Field class for the Joomla Framework.
 */
class JFormFieldMinifyexclusion extends JFormField
{

    /**
     * Exclude JS input
     */
    protected function getInput()
    {

        $html = $style = $script = array();
        // Build style
        $style[] = '
                #minify-file-exclusion p {
                    font-size : 15px;
                    margin-bottom : 10px;
                }
                .js-exclusion,.css-exclusion {
                    margin-left : 75px;
                }
                .input-append label {
                    font-size : 14px;
                    text-transform : uppercase;
                    font-weight : bold;
                    margin-bottom : 10px;
                }
                .minify-exclude-list {
                    margin : 0px;
                }
                .minify-exclude-list li {
                    margin-top : 5px;
                }
                .minify-exclude-text {
                    width : 350px;
                }
                .minify-exclude-input {
                    margin-top : 10px;
                    text-transform : uppercase;
                }
        ';
        // Build script
        $input_js = '<li><input type="text" class="minify-exclude-text ex-js" value="" name="';
        $input_js .= $this->name.'[js][]" /></li>';
        $input_css = '<li><input type="text" class="minify-exclude-text ex-css" value="" name="';
        $input_css .=  $this->name.'[css][]" /></li>';
        $script[] = 'jQuery("document").ready(function($){
                           $("#exclude-js-input").click(function(){
                                var last_element = $( ".ex-js-list li" ).last();
                                if (last_element.find( ".ex-js" ).val() == "") {
                                    $(".ex-js").focus();
                                } else {
                                    $(".ex-js-list").append(\''.$input_js.'\');
                                }
                           });
                           
                           $("#exclude-css-input").click(function(){
                                var last_element = $( ".ex-css-list li" ).last();
                                if (last_element.find( ".ex-css" ).val() == "") {
                                    $(".ex-css").focus();
                                } else {
                                    $(".ex-css-list").append(\''.$input_css.'\');
                                }
                           });
                        });';
        // Add to document head
        JFactory::getDocument()->addScriptDeclaration(implode("\n", $script));
        JFactory::getDocument()->addStyleDeclaration(implode("\n", $style));

        // Create field
        $html[] = '<div id="minify-file-exclusion">';
        $html[] = '<p><i>'.JText::_('COM_SPEEDCACHE_CONFIG_EXCLUDE_NOTICE').'</i></p>';
        // Create JS field
        //
        //
        $html[] = '<div class="input-append js-exclusion">';
        $html[] = '<label>'.JText::_('COM_SPEEDCACHE_CONFIG_EXCLUDE_JS_LABEL').'</label>';
        $html[] = '<ul class="minify-exclude-list ex-js-list">';
        if (!empty($this->value)) {
            $this->value['js'] = array_unique($this->value['js']);
            foreach ($this->value['js'] as $val) {
                $placeholder = '';
                if ($val == '') {
                    $placeholder = 'placeholder="/media/assets/js/frontend.js"';
                }
                $html[] = '<li>';
                $html[] = '<input type="text" class="minify-exclude-text ex-js" value="'.$val.'"';
                $html[] = $placeholder.'name="'.$this->name.'[js][]" />';
                $html[] = '</li>';
            }
        } else {
            $html[] = '<li>';
            $html[] = '<input type="text" class="minify-exclude-text ex-js" value=""';
            $html[] = 'placeholder="/media/assets/js/frontend.js" name="'.$this->name.'[js][]" />';
            $html[] = '</li>';
        }
        $html[] = '</ul>';
        $html[] = '<input type="button" class="btn btn-primary minify-exclude-input" value="';
        $html[] = JText::_('COM_SPEEDCACHE_CONFIG_EXCLUDE_ADDNEW').'" id="exclude-js-input" />';
        $html[] = '</div>';
        $html[] = '<br><br><br>';
        // Create CSS field
        //
        //
        $html[] = '<div class="input-append css-exclusion">';
        $html[] = '<label>'.JText::_('COM_SPEEDCACHE_CONFIG_EXCLUDE_CSS_LABEL').'</label>';
        $html[] = '<ul class="minify-exclude-list ex-css-list">';
        if (!empty($this->value)) {
            $this->value['css'] = array_unique($this->value['css']);
            foreach ($this->value['css'] as $val) {
                $placeholder = '';
                if ($val == '') {
                    $placeholder = 'placeholder="/media/assets/css/frontend.css"';
                }
                $html[] = '<li>';
                $html[] = '<input type="text" class="minify-exclude-text ex-css" value="'.$val.'"';
                $html[] = $placeholder.'name="'.$this->name.'[css][]" />';
                $html[] = '</li>';
            }
        } else {
            $html[] = '<li>';
            $html[] = '<input type="text" class="minify-exclude-text ex-css" value=""';
            $html[] = 'placeholder="/media/assets/css/frontend.css" name="'.$this->name.'[css][]" />';
            $html[] = '</li>';
        }

        $html[] = '</ul>';
        $html[] = '<input type="button" class="btn btn-primary minify-exclude-input" value="';
        $html[] = JText::_('COM_SPEEDCACHE_CONFIG_EXCLUDE_ADDNEW').'" id="exclude-css-input" />';
        $html[] = '</div>';
        $html[] = '</div>';
        return implode("\n", $html);
    }
}
