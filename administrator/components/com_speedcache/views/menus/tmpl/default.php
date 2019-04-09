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

JHtml::_('jquery.framework');
?>

    <script>
        jQuery('document').ready(function ($) {
            //Checkbox check on click
            $('span.check').click(function () {
                $this = $(this);
                if ($this.hasClass('checked')) {
                    $this.removeClass('checked');
                    $this.siblings('ul').find('.check').removeClass('checked');

                    $this.siblings('input[type=checkbox]').attr('checked', null);
                    $this.siblings('ul').find('input[type=checkbox]').attr('checked', null);
                } else {
                    $this.addClass('checked');
                    $this.siblings('ul').find('.check').addClass('checked');

                    $this.siblings('input[type=checkbox]').attr('checked', 'checked');
                    $this.siblings('ul').find('input[type=checkbox]').attr('checked', 'checked');
                }
                $('#count').html($('#sc-menu-items input[type=checkbox][data-type=item]:checked').length);
            });

            $('a.title').click(function (e) {
                e.preventDefault();
                $parent = $(this).parent();
                if ($parent.hasClass('expanded')) {
                    $parent.removeClass('expanded');
                } else {
                    $parent.addClass('expanded');
                }
            });

            $('#select-all').click(function (e) {
                e.preventDefault();
                $('#sc-menu-items input[type=checkbox]').attr('checked', 'checked');
                $('#sc-menu-items span.check').addClass('checked');
                $('#count').html($('#sc-menu-items input[type=checkbox][data-type=item]:checked').length);
            });

            $('#unselect-all').click(function (e) {
                e.preventDefault();
                $('#sc-menu-items input[type=checkbox][data-type=item]').attr('checked', null);
                $('#sc-menu-items span.check').removeClass('checked');
                $('#count').html($('#sc-menu-items input[type=checkbox][data-type=item]:checked').length);
            });

            //Submit or cancel
            $('.display-add-button').on('click', '#add', function (e) {
                e.preventDefault();
                items = [];
                $('#sc-menu-items input[type=checkbox][data-type=item]:checked').each(function () {
                    items.push($(this).val());
                });
                $.ajax({
                    url: 'index.php?option=com_speedcache&task=url.saveMany&<?php echo JSession::getFormToken(); ?>=1',
                    type: 'POST',
                    data: {
                        items: items
                    },
                    success: function () {
                        window.parent.location.reload();
                    }
                });
            });
            $('.display-add-button').on('click', '#add_urlexclude', function (e) {
                e.preventDefault();
                items = [];
                $('#sc-menu-items input[type=checkbox][data-type=item]:checked').each(function () {
                    items.push($(this).val());
                });
                $.ajax({
                    url: 'index.php?option=com_speedcache&task=url.saveUrlExclude&<?php echo JSession::getFormToken(); ?>=1',
                    type: 'POST',
                    data: {
                        items: items
                    },
                    success: function () {
                        window.parent.location.reload();
                    }
                });
            });

            $('#cancel').click(function (e) {
                e.preventDefault();
                window.parent.SqueezeBox.close();
            });

            var lastTab = localStorage.getItem('lastTab');
            if (lastTab == null || lastTab.indexOf('url_include') > -1) {
                html = '<a href="#" id="add" class="material-button blue"><?php echo JText::_('COM_SPEEDCACHE_MENUS_CONFIRM'); ?></a>';
            } else {
                html = '<a href="#" id="add_urlexclude" class="material-button blue"><?php echo JText::_('COM_SPEEDCACHE_MENUS_SELECT'); ?></a>';
            }
            $('.display-add-button').append(html);
        });
    </script>

    <div class="buttons-wrapper display-add-button">
        <div id="count-wrapper">
            <span id="count">0</span> <?php echo JText::_('COM_SPEEDCACHE_MENUS_COUNT'); ?>
        </div>
        <a href="#" id="cancel" class="material-button"><?php echo JText::_('COM_SPEEDCACHE_MENUS_CANCEL'); ?></a>
    </div>

    <div class="select-wrapper">
        <a href="#" id="select-all"><?php echo JText::_('COM_SPEEDCACHE_SELECT_ALL'); ?></a>
        <a href="#" id="unselect-all"><?php echo JText::_('COM_SPEEDCACHE_UNSELECT_ALL'); ?></a>
    </div>

<?php
//Start the main list containing all menus
echo "<ul id='sc-menu-items'>";

//List all menu
foreach ($this->menus as $menu) {
    if(empty($menu) || !isset($menu[0]) ) {
        continue;
    }
    echo "<li class='menu hasitems'><input type='checkbox' data-type='menu'/><span class='check'></span><a class='title' href='#'>" . $menu[0]->menu_title . "</a>\n";

    $lastLevel = null;
    foreach ($menu as $item) {

        //Same level close the previous li element
        if ($lastLevel === $item->level) {
            echo "</li>\n";

        } //Last level was higher close previous elements (li) and lists (ul)
        elseif ($lastLevel > $item->level) {
            for ($i = 0; $i < ($lastLevel - $item->level); $i++) {
                echo "</li>\n</ul>\n";
            }
            echo "</li>\n";
        } //Last level is lower open the new list
        elseif ($lastLevel < $item->level) {
            echo "<ul>\n";
        }

        if (isset($item->link) && $item->link) {
            echo "<li class='expanded " . (($item->rgt - $item->lft) > 1 ? "hasitems" : "") . "'><input type='checkbox' data-type='item' value='" . $item->flink . "'/><span class='check'></span><span class='icon'></span><a class='title' href='#'>" . $item->title . "</a> (<a class='link' target='_blank' href='" . $item->flink . "'>" . $item->flink . "</a>)";
        } else {
            //This is an item without link
            echo "<li class='expanded " . (($item->rgt - $item->lft) > 1 ? "hasitems" : "") . "'><input type='checkbox' data-type='item-noselect' value=''/><span class='check'></span><span class='icon'></span><a class='title' href='#'>" . $item->title . "</a>";
        }

        $lastLevel = $item->level;
    }

    //Close all sub levels until end
    for ($i = 0; $i < ($lastLevel - 1); $i++) {
        echo "</li></ul>\n";
    }

    echo "</li></ul>\n"; //End menu items list

    echo "</li>\n"; // End menu
}

//End the main menu list
echo "</ul>";
?>