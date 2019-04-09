<?php
/**
 * Speedcache
 *
 * We developed this code with our hearts and passion.
 * We hope you found it useful, easy to understand and to customize.
 * Otherwise, please feel free to contact us at contact@joomunited.com *
 * @package JUUpdater
 * @copyright Copyright (C) 2016 JoomUnited (https://www.joomunited.com). All rights reserved.
 * @license GNU General Public License version 2 or later; http://www.gnu.org/licenses/gpl-2.0.html
 */

defined('_JEXEC') or die;

jimport('joomla.form.formfield');


class JFormFieldImport extends JFormField
{
    protected function getInput()
    {
        // Load modal behavior
        JHtml::_('behavior.modal', 'a.modal');

        $style = array();
        $style[] = '.ju-btn {
                            text-shadow: 0 -1px 0 rgba(0,0,0,0.25);
                            display: inline-block;
                            padding: 6px 40px;
                            margin-bottom: 0;
                            font-size: 15px;
                            font-weight: 400;
                            line-height: 18px;
                            text-align: center;
                            white-space: nowrap;
                            vertical-align: middle;
                            touch-action: manipulation;
                            cursor: pointer;
                            -webkit-user-select: none;
                            background-image: none;
                            border: 1px solid transparent;
                            border-radius: 4px;
                            text-transform: none;
                            text-decoration: none;
                            font: inherit;
                            margin: 0;
                            overflow: visible;
                            text-transform: uppercase;
                        }
                        .ju-btn:hover{
                            color: #fff;
                        }
                        .ju-btn-import {
                            color: #fff;
                            background-color: #bd362f;
                            border-color: #bd362f;
                        }

                        .ju-btn-import:hover{
                            background-color: #832627;
                        }';


        //Add to document head
        JFactory::getDocument()->addStyleDeclaration(implode("\n", $style));

        $links = 'index.php?option=com_speedcache&amp;view=configs&amp;layout=import&amp;tmpl=component';
        $html = array();
        // The book select button
        $html[] = '<div class="button2-left">';
        $html[] = '  <div class="blank">';
        $html[] = '<a style="text-decoration:none;" 
class="btn button modal ju-btn ju-btn-import" 
title="Export configuration" 
href="'.$links.'" 
rel="{handler: \'iframe\', size: {x:450, y:250}}">Import</a>';
        $html[] = '  </div>';
        $html[] = '</div>';

        return implode("\n", $html);
    }

}
