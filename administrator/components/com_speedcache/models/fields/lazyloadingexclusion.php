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
class JFormFieldLazyloadingexclusion extends JFormField
{
    /**
     * Exclude Lazy loading input
     */
    protected function getInput()
    {
        $html = $style = $script = array();
        // Build style
        $style[] = '
                #lazyloading-exclusion p {
                    font-size : 13px;
                    margin-bottom : 10px;
                }
                .lazyloading-exclusion {
                    margin-left : 75px;
                }
                .input-append label {
                    font-size : 14px;
                    text-transform : uppercase;
                    font-weight : bold;
                    margin-bottom : 10px;
                }
                .lazyload-exclude-list {
                    margin : 0px;
                }
                .lazyload-exclude-list li {
                    margin-top : 5px;
                }
                .lazyload-exclude-text {
                    width : 500px;
                }
                .lazyload-exclude-input {
                    margin-top : 10px;
                    text-transform : uppercase;
                }
        ';
        // Build script
        $input = '<li><input type="text" class="lazyload-exclude-text ex-lazy" value="" name="';
        $input .= $this->name.'[]" /></li>';

        $script[] = 'jQuery("document").ready(function($){
                           $("#exclude-lazy-input").click(function(){
                                var last_element = $( ".ex-lazy-list li" ).last();
                                if (last_element.find( ".ex-lazy" ).val() == "") {
                                    $(".ex-lazy").focus();
                                } else {
                                    $(".ex-lazy-list").append(\''.$input.'\');
                                }
                           });
                        });';
        // Add to document head
        JFactory::getDocument()->addScriptDeclaration(implode("\n", $script));
        JFactory::getDocument()->addStyleDeclaration(implode("\n", $style));

        // Create field
        $html[] = '<div id="lazyloading-exclusion">';

        $html[] = '<p>'.JText::_('COM_SPEEDCACHE_CONFIG_EXCLUDE_LAZY_LOADING_DESC').'</p>';
        // Create list field
        $html[] = '<div class="input-append lazyloading-exclusion">';
        $html[] = '<ul class="lazyload-exclude-list ex-lazy-list">';
        if (!empty($this->value)) {
            $this->value = array_unique($this->value);
            $count = count($this->value);
            foreach ($this->value as $k => $val) {
                if ($count == 1 && empty($val)) {
                    $html[] = '<li>';
                    $html[] = '<input type="text" class="lazyload-exclude-text ex-lazy" value=""';
                    $html[] = 'placeholder="www.website.com/news*" name="'.$this->name.'[]" />';
                    $html[] = '</li>';
                } else {
                    if (empty($val)) {
                        continue;
                    }
                    $html[] = '<li>';
                    $html[] = '<input type="text" class="lazyload-exclude-text ex-lazy" value="'.$val.'"';
                    $html[] = 'placeholder="www.website.com/news*" name="'.$this->name.'[]" />';
                    $html[] = '</li>';
                }

            }
        } else {
            $html[] = '<li>';
            $html[] = '<input type="text" class="lazyload-exclude-text ex-lazy" value=""';
            $html[] = 'placeholder="www.website.com/news*" name="'.$this->name.'[]" />';
            $html[] = '</li>';
        }
        $html[] = '</ul>';
        $html[] = '<input type="button" class="btn btn-primary lazyload-exclude-input" value="';
        $html[] = JText::_('COM_SPEEDCACHE_CONFIG_EXCLUDE_ADDNEW').'" id="exclude-lazy-input" />';
        $html[] = '</div>';
        $html[] = '<br>';
        $html[] = '</div>';
        return implode("\n", $html);
    }
}
