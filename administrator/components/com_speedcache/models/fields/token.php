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

jimport('joomla.form.formfield');

class JFormFieldToken extends JFormFieldText
{

    protected $type = 'Token';

    /**
     */
    protected function getInput()
    {

        $class = $this->element['class'] ? ' ' . (string)$this->element['class'] . '' : '';

        $baseUrl = JUri::root() . 'index.php?option=com_speedcache&task=preload&token=';
        $script = 'jQuery("document").ready(function($){
                            var tokenBaseUrl="' . $baseUrl . '";
                            $(".token_reset").click(function(e){
                                e.preventDefault();
                                id = $(this).data("input");
                                token = Math.random(30).toString(36).slice(2);
                                $("#"+id).val(token);
                                $(".token_"+id).html(tokenBaseUrl+token).attr("href",tokenBaseUrl+token);
                            });
                        });';
        JFactory::getDocument()->addScriptDeclaration($script);


        return '<input type="text" name="' . $this->name . '" id="' . $this->id . '" value="' . $this->value . '" readonly class=" ' . $class . '">
                    <a class="btn token_reset" href="#" data-input="' . $this->id . '">' . JText::_('COM_SPEEDCACHE_CONFIG_TOKEN_RESET_BUTTON') . '</a>
                    <p class="sc-preload-url" style="margin-top: 10px;">
                    You can call the regenerate page through : <a target="_blank" class="token_' . $this->id . '" href="' . $baseUrl . htmlentities($this->value) . '">' . $baseUrl . htmlentities($this->value) . '</a>
                    </p>';
    }

}
