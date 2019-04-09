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

// Include the component HTML helpers.
JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');
JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('formbehavior.chosen', 'select');

JHtml::_('jquery.framework');
JHTML::_('behavior.modal');

JFactory::getDocument()->addStyleSheet(JUri::base() . 'components/com_speedcache/assets/css/minifications.css');
// Tooltip css
JFactory::getDocument()->addStyleSheet(JUri::base() . 'components/com_speedcache/assets/css/jquery.qtip.css');
JFactory::getDocument()->addScript(JUri::base() . 'components/com_speedcache/assets/js/jquery.qtip.min.js');
JFactory::getDocument()->addScript(JUri::base() . 'components/com_speedcache/assets/js/urls.js');
JFactory::getDocument()->addScript(JUri::base() . 'components/com_speedcache/assets/js/minifications.js');

$user = JFactory::getUser();
$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn = $this->escape($this->state->get('list.direction'));
?>
<script>
    jQuery('document').ready(function ($) {
        //        scan
        $('#scan-asset').click(function () {
            $.ajax({
                url: 'index.php?option=com_speedcache&view=minifications&task=minifications.getAllowedPath',
                method: 'POST',
                success: function (path) {
                    var path = $.parseJSON(path);
                    if (path.status) {
                        alert('Error scan!');
                        window.location.reload(true);
                    }
                    var i = 0;
                    var scan = function (dir, path) {
                        $.ajax({
                            url: 'index.php?option=com_speedcache&view=minifications&task=minifications.getFileAssets',
                            method: 'POST',
                            data: {
                                dir: dir
                            },
                            success: function (res) {
                                i++;
                                if (i < path.length) {
                                    scan(path[i], path);
                                } else {
                                    window.location.reload(true);
                                }

                            }
                        });
                    };
                    scan(path[i], path);
                }
            });
        });

        //change minify state
        $('.speedcache-optimization').change(function () {
            var minify = $(this).val();
            var id = $(this).data("id");
            $.ajax({
                url: 'index.php?option=com_speedcache&view=minifications&task=minifications.changeMinify',
                method: 'POST',
                data: {
                    minify: minify,
                    id: id
                }, success: function () {
                    window.location.reload(true);
                }
            });
        });

        //minify all
        $('#toggle-state').click(function () {
            var ids = [];
            $('input[type=checkbox]:checked').each(function () {
                ids.push($(this).val());
            });
            $.ajax({
                url: 'index.php?option=com_speedcache&view=minifications&'+
                'task=minifications.activeAll&<?php echo JSession::getFormToken(); ?>=1',
                method: 'POST',
                data: {
                    ids: ids
                },
                success: function (res) {
                    window.location.reload(true);
                }
            });
        });

        //Change state group
        $('.group-minify-field').click(function () {
            var state = $(this).val();
            var type = $(this).attr('name');

            $.ajax({
                url: 'index.php?option=com_speedcache&view=minifications&'+
                'task=minifications.changeStateGroup&<?php echo JSession::getFormToken(); ?>=1',
                method: 'POST',
                data: {
                    state: state,
                    type: type
                },
                success: function (res) {
                    window.location.reload(true);
                }
            });
        });

        $('input[name=filter_type]').change(function () {
            var id = $(this).attr('id');
            $('#filter-switch-minifications').find('label').removeClass();
            if (id == 'all') {
                $("[for=all]").addClass('all');
            }
            if (id == 'sc-js-minifications') {
                $("[for=sc-js-minifications]").addClass('sc-js-minifications');
            }
            if (id == 'sc-css-minifications') {
                $("[for=sc-css-minifications]").addClass('sc-css-minifications');
            }
            if (id == 'sc-font-minifications') {
                $("[for=sc-font-minifications]").addClass('sc-font-minifications');
            }

            this.form.submit();
        });

        var filterselected = $('input[name=filter_type]:checked').val();
        if (filterselected == '2') {
            $("[for=sc-js-minifications]").addClass('sc-js-minifications');
        } else if (filterselected == '1') {
            $("[for=sc-css-minifications]").addClass('sc-css-minifications');
        } else if (filterselected == '0') {
            $("[for=sc-font-minifications]").addClass('sc-font-minifications');
        } else {
            $("[for=all]").addClass('all');
        }
    });

</script>


<?php
$session = JFactory::getSession();
$message = $session->get('speedache_message');
$session->clear('speedache_message');
if ($message) :
    ?>
    <div class="alert alert-success">
        <div class="alert-message"><?php echo $message['message']; ?></div>
    </div>
<?php endif; ?>

<form action="<?php echo JRoute::_('index.php?option=com_speedcache&view=minifications'); ?>" method="post"
      name="adminForm" id="adminForm">

    <div id="j-main-container" class="span10">
        <div id="j-main-container">
            <div id="page-description" class="page-description">
                <span><i>The file minification is an advanced optimization feature.
                        Please run a full test on your website before keeping minification activated :)
                    </i></span>
                <br>
                <span><i>
                        Use the Speed Cache settings to Include/Exclude some new files and run a new scan.
                        <a href="https://www.joomunited.com/documentation/speed-cache-documentation"
                                target="_blank"> OPEN DOCUMENTATION >></a>
                    </i></span>
            </div>
            <div id="groupFileField">
                <table cellpadding="5">
                    <tr>
                        <td>
                            <label class="group-label speedcache_tooltip" for="grcss-minify"
                                   alt="<?php echo JText::_('COM_SPEEDCACHE_CONFIG_GROUP_CSS_DESC'); ?>">
                                <?php echo JText::_('COM_SPEEDCACHE_GROUP_MINIFICATIONS_CSS') ?>
                            </label>
                        </td>
                        <td>
                            <div class="switch-optimization">
                                <label class="switch ">
                                    <input data-id='' type="checkbox"
                                           class="group-minify-field" id="grcss-minify"
                                           name="minify_group_css"
                                           value="<?php echo $this->groupCss; ?>"
                                            <?php
                                            if ($this->groupCss == '1') {
                                                echo 'checked="checked"';
                                            }
                                            ?>
                                    >
                                    <div class="slider round"></div>
                                </label>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label class="group-label speedcache_tooltip" for="grjs-minify"
                                   alt="<?php echo JText::_('COM_SPEEDCACHE_CONFIG_GROUP_JS_DESC'); ?>">
                                <?php echo JText::_('COM_SPEEDCACHE_GROUP_MINIFICATIONS_JS') ?>
                            </label>
                        </td>
                        <td>
                            <div class="switch-optimization">
                                <label class="switch ">
                                    <input data-id='' type="checkbox"
                                           class="group-minify-field" id="grjs-minify"
                                           name="minify_group_js"
                                           value="<?php echo $this->groupJS; ?>"
                                            <?php
                                            if ($this->groupJS == '1') {
                                                echo 'checked="checked"';
                                            }
                                            ?>
                                    >
                                    <div class="slider round"></div>
                                </label>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label class="group-label speedcache_tooltip" for="grfont-minify"
                                   alt="<?php echo JText::_('COM_SPEEDCACHE_CONFIG_GROUP_FONT_DESC'); ?>">
                                <?php echo JText::_('COM_SPEEDCACHE_GROUP_MINIFICATIONS_FONT') ?>
                            </label>
                        </td>
                        <td>
                            <div class="switch-optimization">
                                <label class="switch ">
                                    <input data-id='1' type="checkbox"
                                           class="group-minify-field" id="grfont-minify"
                                           name="minify_group_fonts"
                                           value="<?php echo $this->groupFont; ?>"
                                            <?php
                                            if ($this->groupFont == '1') {
                                                echo 'checked="checked"';
                                            }
                                            ?>
                                    >
                                    <div class="slider round"></div>
                                </label>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label class="group-label speedcache_tooltip" for="defer-css"
                                   alt="<?php echo JText::_('COM_SPEEDCACHE_CONFIG_DEFER_CSS_DESC'); ?>">
                                <?php echo JText::_('COM_SPEEDCACHE_CONFIG_DEFER_CSS_LABEL') ?>
                            </label>
                        </td>
                        <td>
                            <div class="switch-optimization">
                                <label class="switch ">
                                    <input data-id='' type="checkbox"
                                           class="group-minify-field" id="defer-css"
                                           name="defer_css"
                                           value="<?php echo $this->deferCSS; ?>"
                                        <?php
                                        if ($this->deferCSS == '1') {
                                            echo 'checked="checked"';
                                        }
                                        ?>
                                    >
                                    <div class="slider round"></div>
                                </label>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label class="group-label speedcache_tooltip" for="defer-js"
                                   alt="<?php echo JText::_('COM_SPEEDCACHE_CONFIG_DEFER_JS_DESC'); ?>">
                                <?php echo JText::_('COM_SPEEDCACHE_CONFIG_DEFER_JS_LABEL') ?>
                            </label>
                        </td>
                        <td>
                            <div class="switch-optimization">
                                <label class="switch ">
                                    <input data-id='' type="checkbox"
                                           class="group-minify-field" id="defer-js"
                                           name="defer_js"
                                           value="<?php echo $this->deferJS; ?>"
                                        <?php
                                        if ($this->deferJS == '1') {
                                            echo 'checked="checked"';
                                        }
                                        ?>
                                    >
                                    <div class="slider round"></div>
                                </label>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
            <br>
            <div id="filter-switch-minifications">
                <fieldset>
                    <div class="switch-toggle well">
                        <?php
                        $checked = ($this->state->get('filter.type') == " ") ? "checked='checked'" : ""; ?>
                        <input id="all" name="filter_type" type="radio" <?php echo $checked; ?> value=''>
                        <label for="all">All</label>

                        <?php $checked = ($this->state->get('filter.type') == "2") ? "checked='checked'" : ""; ?>
                        <input id="sc-js-minifications" name="filter_type" type="radio"
                               value="2" <?php echo $checked; ?>>
                        <label for="sc-js-minifications">
                            <?php echo JText::_('COM_SPEEDCACHE_MINIFICATIONS_JAVASCRIPT') ?>
                        </label>

                        <?php $checked = ($this->state->get('filter.type') == "1") ? "checked='checked'" : ""; ?>
                        <input id="sc-css-minifications" name="filter_type" type="radio"
                               value="1" <?php echo $checked; ?>>
                        <label for="sc-css-minifications">
                            <?php echo JText::_('COM_SPEEDCACHE_MINIFICATIONS_CSS') ?>
                        </label>

                        <?php $checked = ($this->state->get('filter.type') == "0") ? "checked='checked'" : ""; ?>
                        <input id="sc-font-minifications" name="filter_type" type="radio"
                               value="0" <?php echo $checked; ?>>
                        <label for="sc-font-minifications">
                            <?php echo JText::_('COM_SPEEDCACHE_MINIFICATIONS_FONT') ?>
                        </label>

                        <a class="btn btn-primary"></a>
                    </div>
                </fieldset>
            </div>
            <div id="filter-minify-search">
                <div class=" pull-left">
                    <button type="submit" class="btn hasTooltip"
                            title="<?php echo JHtml::tooltipText('JSEARCH_FILTER_SUBMIT'); ?>"><span
                                class="icon-search"></span></button>
                </div>
                <div class="filter-search pull-left">
                    <input type="text" name="filter_search" id="filter_search"
                           placeholder="<?php echo JText::_('COM_SPEEDCACHE_MINIFICATIONS_JSEARCH_FILTER'); ?>"
                           value="<?php echo $this->escape($this->state->get('filter.search')); ?>" class="hasTooltip"
                           title="<?php echo JHtml::tooltipText(
                               'COM_SPEEDCACHE_MINIFICATIONS_JSEARCH_FILTER_TOOLTIP'
                           ); ?>"/>
                </div>
            </div>
            <div id="scan-minifications">
                <div class="pull-right hidden-phone switch-optimization">
                    <table>
                        <tr>
                            <td>
                                <a class="material-button" id="toggle-state"
                                   href="#"><i class="material-icons icon-minifications">check</i>
                                    <span><?php echo JText::_('COM_SPEEDCACHE_MINIFICATION_TOGGLE_STATE'); ?></span></a>
                            </td>
                            <td>
                                <a class="material-button" id="scan-asset"
                                   href="#"><i class="material-icons icon-minifications">cached</i>
                                    <span><?php echo JText::_('COM_SPEEDCACHE_MINIFICATION_SCAN'); ?></span></a>
                            </td>
                        </tr>
                    </table>

                </div>
            </div>
            <div class="clearfix"></div>
            <br>
            <?php if (empty($this->items)) : ?>
                <div class="alert alert-no-items">
                    <?php echo JText::_('JGLOBAL_NO_MATCHING_RESULTS'); ?>
                </div>
            <?php else: ?>
                <div class="form-horizontal">
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th width="4%" class="center">
                                <?php echo JHtml::_(
                                    'grid.checkall',
                                    'checkall-toggle',
                                    'JGLOBAL_CHECK_ALL',
                                    'joomla_sc.checkAll(this)'
                                ); ?>
                            </th>
                            <th class="6%">
                                <?php echo JHtml::_(
                                    'grid.sort',
                                    JText::_('COM_SPEEDCACHE_MINIFICATION_TYPE'),
                                    'a.type',
                                    $listDirn,
                                    $listOrder
                                ); ?>
                            </th>
                            <th width="78%" class="center">
                                <?php echo JHtml::_(
                                    'grid.sort',
                                    JText::_('COM_SPEEDCACHE_MINIFICATION_FILE'),
                                    'a.file',
                                    $listDirn,
                                    $listOrder
                                ); ?>
                            </th>
                            <th width="12%" class="center">
                                <?php echo JHtml::_(
                                    'grid.sort',
                                    JText::_('COM_SPEEDCACHE_MINIFICATION_MINIFY'),
                                    'a.minify',
                                    $listDirn,
                                    $listOrder
                                ); ?>
                            </th>
                        </tr>
                        </thead>
                        <tfoot>
                        <tr>
                            <td colspan="3">
                                <?php echo $this->pagination->getListFooter(); ?>
                            </td>
                            <td>
                                <div class="display-limit pull-right">
                                    <?php echo JText::_('JGLOBAL_DISPLAY_NUM'); ?>&#160;
                                    <?php echo $this->pagination->getLimitBox(); ?>
                                </div>
                            </td>
                        </tr>
                        </tfoot>
                        <tbody>
                        <?php foreach ($this->items as $i => $item) :
                            ?>
                            <tr class="row<?php echo $i % 2; ?>">
                                <td class="center">
                                    <?php echo JHtml::_('grid.id', $i, $item->id); ?>
                                </td>
                                <td>
                                    <?php
                                    if ($item->type == 0) {
                                        $type = 'FONT';
                                    } elseif ($item->type == 1) {
                                        $type = 'CSS';
                                    } else {
                                        $type = 'JS';
                                    }
                                    ?>
                                    <span class="sc-filename-extension sc-filename-extension-<?php
                                    echo strtolower($type); ?>">
                                        <?php echo JText::_('COM_SPEEDCACHE_MINIFICATION_' . $type); ?></span>
                                </td>
                                <td class="file-tab">
                                    <span class="file"><?php echo $this->escape($item->file); ?></span>
                                </td>
                                <td class="center">
                                    <div class="switch-optimization">
                                        <label class="switch ">
                                            <input data-id='<?php echo $item->id; ?>' type="checkbox"
                                                   class="speedcache-optimization" id="active-minify"
                                                   name="active-minify"
                                                   value="<?php echo $item->minify ?>"
                                                    <?php
                                                    if ($item->minify) {
                                                        if ($item->minify == 1) {
                                                            echo 'checked="checked"';
                                                        }
                                                    }
                                                    ?>
                                            >
                                            <div class="slider round"></div>
                                        </label>
                                    </div>
                                </td>
                            </tr>
                            <?php
                        endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
            <input type="hidden" name="task" value=""/>
            <input type="hidden" name="boxchecked" value="0"/>
            <input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>"/>
            <input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>"/>
            <?php echo JHtml::_('form.token'); ?>
        </div>
    </div>
</form>
