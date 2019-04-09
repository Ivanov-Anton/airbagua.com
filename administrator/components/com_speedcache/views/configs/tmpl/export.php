<?php

/**
 * Speedcache
 *
 * We developed this code with our hearts and passion.
 * We hope you found it useful, easy to understand and to customize.
 * Otherwise, please feel free to contact us at contact@joomunited.com *
 * @package ImageRecycle
 * @copyright Copyright (C) 2014 JoomUnited (http://www.joomunited.com). All rights reserved.
 * @license GNU General Public License version 2 or later; http://www.gnu.org/licenses/gpl-2.0.html
 */

defined('_JEXEC') or die;
?>
<div>
    <?php if ($this->canDo->get('core.admin')) :?>
    <div class="configs-title">
        <label><?php echo JText::_('COM_SPEEDCACHE_CLICK_TO_EXPORT_TITLE') ?></label>
    </div>
    <div class="configs-type">
        <label class="notice-text">
            The system will export the configuration to a json file.
        </label>
        <label for="input_import_file">Data format: <b>JSON</b></label>

    </div>
    <br>
    <div class="configs-type-button">
        <button class="btn btn-primary click-to" type="button"
                id="click-to-export"><?php echo JText::_('COM_SPEEDCACHE_CLICK_TO_EXPORT') ?></button>
        <button class="btn" type="button"
                onclick="window.parent.jModalClose();"><?php echo JText::_('JCANCEL') ?></button>
    </div>
    <?php endif; ?>
</div>

<script>
    jQuery('document').ready(function ($) {
        $('#click-to-export').click(function () {
            $.ajax({
                url: 'index.php?option=com_speedcache&view=configs&task=configs.exportConfigurations&<?php echo JSession::getFormToken(); ?>=1',
                method: 'POST',
                success: function (res) {
                    res = $.parseJSON(res);
                    if (res.status === 'success') {
                        var blob=new Blob([res.params]);
                        var link=document.createElement('a');
                        link.href=window.URL.createObjectURL(blob);
                        link.download="speedcache.json";
                        link.click();
                    } else if(res.status === 'Permission denied'){
                        alert('Permission denied!');
                    }
                }
            });
        });

    });
</script>