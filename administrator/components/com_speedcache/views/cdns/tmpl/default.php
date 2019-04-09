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


JFactory::getDocument()->addStyleSheet(JUri::base() . 'components/com_speedcache/assets/css/minifications.css');
// Tooltip css
JFactory::getDocument()->addStyleSheet(JUri::base() . 'components/com_speedcache/assets/css/jquery.qtip.css');
JFactory::getDocument()->addScript(JUri::base() . 'components/com_speedcache/assets/js/jquery.qtip.min.js');
JFactory::getDocument()->addScript(JUri::base() . 'components/com_speedcache/assets/js/minifications.js');
?>
<script>
    jQuery('document').ready(function ($) {
        $('.cdn-checkbox').click(function(){
            var value = $(this).val();
            if (value == 0) {
                $(this).val(1);
            } else {
              $(this).val(0);
            }
        });
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

<form action="<?php echo JRoute::_('index.php?option=com_speedcache&view=cdn'); ?>" method="post" name="adminForm"
     id="adminForm">
    <div id="cdn-configuration">
        <table width="100%" cellpadding="10">
            <tr>
                <td>
                    <label class="group-label speedcache_tooltip" for="cdn-active"
                           alt="<?php echo JText::_('COM_SPEEDCACHE_CONFIG_CDN_ACTIVE_DESC'); ?>">
                        <?php echo JText::_('COM_SPEEDCACHE_CONFIG_CDN_ACTIVE_LABEL') ?>
                    </label>
                </td>
                <td>
                    <div class="switch-optimization">
                        <label class="switch ">
                            <input data-id='' type="checkbox"
                                   class="cdn-checkbox" id="cdn-active"
                                   name="cdn_active"
                                   value="<?php echo $this->cdn_active; ?>"
                                <?php
                                if ($this->cdn_active == '1') {
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
                    <label class="group-label speedcache_tooltip" for="cdn-url"
                           alt="<?php echo JText::_('COM_SPEEDCACHE_CONFIG_CDN_URL_DESC'); ?>">
                        <?php echo JText::_('COM_SPEEDCACHE_CONFIG_CDN_URL_LABEL') ?>
                    </label>
                </td>
                <td>
                    <input type="text" placeholder="https://www.domain.com" value="<?php echo $this->cdn_url; ?>"
                           name="cdn_url" id="cdn-url"/>
                </td>
            </tr>
            <tr>
                <td>
                    <label class="group-label speedcache_tooltip" for="cdn-content"
                           alt="<?php echo JText::_('COM_SPEEDCACHE_CONFIG_CDN_CONTENT_DESC'); ?>">
                        <?php echo JText::_('COM_SPEEDCACHE_CONFIG_CDN_CONTENT_LABEL') ?>
                    </label>
                </td>
                <td>
                    <input type="text" value="<?php echo $this->cdn_content; ?>"
                           name="cdn_content" id="cdn-content"/>
                </td>
            </tr>
            <tr>
                <td>
                    <label class="group-label speedcache_tooltip" for="cdn-exclude-content"
                           alt="<?php echo JText::_('COM_SPEEDCACHE_CONFIG_CDN_EXCLUDE_CONTENT_DESC'); ?>">
                        <?php echo JText::_('COM_SPEEDCACHE_CONFIG_CDN_EXCLUDE_CONTENT_LABEL') ?>
                    </label>
                </td>
                <td>
                    <input type="text" value="<?php echo $this->cdn_exclude_content; ?>"
                           name="cdn_exclude_content" id="cdn-exclude-content"/>
                </td>
            </tr>
            <tr>
                <td>
                    <label class="group-label speedcache_tooltip" for="cdn-relative-path"
                           alt="<?php echo JText::_('COM_SPEEDCACHE_CONFIG_CDN_RELATIVE_PATH_DESC'); ?>">
                        <?php echo JText::_('COM_SPEEDCACHE_CONFIG_CDN_RELATIVE_PATH_LABEL') ?>
                    </label>
                </td>
                <td>
                    <div class="switch-optimization">
                        <label class="switch ">
                            <input data-id='' type="checkbox"
                                   class="cdn-checkbox" id="cdn-relative-path"
                                   name="cdn_relative_path"
                                   value="<?php echo $this->cdn_relative_path; ?>"
                                <?php
                                if ($this->cdn_relative_path == '1') {
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
    <input type="hidden" name="task" value=""/>
    <?php echo JHtml::_('form.token'); ?>
</form>