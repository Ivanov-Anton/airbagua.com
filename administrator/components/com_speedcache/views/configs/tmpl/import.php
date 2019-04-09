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
        <form action="<?php echo JRoute::_('index.php?option=com_speedcache&task=configs.importConfigurations'); ?>"
              enctype="multipart/form-data"
              method="post">
            <div class="configs-title">
                <label><?php echo JText::_('COM_SPEEDCACHE_CLICK_TO_IMPORT_TITLE') ?></label>
            </div>

            <div class="configs-type">
                <label class="notice-text">
                    Select a Speed Cache <b>.json</b> exported file and import it.
                </label>
                <label for="input_import_file"><b>Select your file:</b></label>
                <input accept=".json" type="file" name="import_file" id="input_import_file" required />
                <input type="hidden" id="input_import_content" value="" />
            </div>
            <br>
            <div class="configs-type-button">
                <button class="btn btn-primary click-to" type="submit"
                        id="click-to-import"><?php echo JText::_('COM_SPEEDCACHE_CLICK_TO_IMPORT') ?></button>
                <button class="btn" type="button"
                        onclick="window.parent.jModalClose();"><?php echo JText::_('JCANCEL') ?></button>
            </div>
        <?php echo JHtml::_('form.token'); ?>
        </form>
    <?php endif; ?>
</div>
