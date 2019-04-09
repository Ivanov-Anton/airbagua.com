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

JFactory::getDocument()->addStyleDeclaration(
    "
	@media (min-width: 768px) {
		div.modal {
			left: none;
			width: 500px;
			margin-left: -250px;
		}
	}
	"
);
?>
<?php
$session = JFactory::getSession();
$message = $session->get('speedache_message');
$session->clear('speedache_message');
if ($message):
    ?>
    <div class="alert alert-success">
        <div class="alert-message"><?php echo $message['message']; ?></div>
    </div>
<?php endif; ?>

<form action="<?php echo JRoute::_('index.php?option=com_speedcache&view=urls'); ?>" method="post" name="adminForm"
      id="adminForm">
    <?php if (!empty($this->sidebar)) : ?>
    <div id="j-sidebar-container" class="span2">
        <?php echo $this->sidebar; ?>
    </div>
    <div id="j-main-container" class="span10">
        <?php else : ?>
        <div id="j-main-container">
            <?php endif; ?>
            <div id="filter-bar" class="btn-toolbar">
                <div class="filter-search btn-group pull-left">
                    <input type="text" name="filter_search" id="filter_search"
                           placeholder="<?php echo JText::_('JSEARCH_FILTER'); ?>"
                           value="<?php echo $this->escape($this->state->get('filter.search')); ?>" class="hasTooltip"
                           title="<?php echo JHtml::tooltipText('COM_SPEEDCACHE_SEARCH_IN_URL'); ?>"/>
                </div>
                <div class="btn-group pull-left">
                    <button type="submit" class="btn hasTooltip"
                            title="<?php echo JHtml::tooltipText('JSEARCH_FILTER_SUBMIT'); ?>"><span
                            class="icon-search"></span></button>
                    <button type="button" class="btn hasTooltip"
                            title="<?php echo JHtml::tooltipText('JSEARCH_FILTER_CLEAR'); ?>"
                            onclick="document.getElementById('filter_search').value='';this.form.submit();"><span
                            class="icon-remove"></span></button>
                </div>
            </div>
            <div class="clearfix"></div>

            <!--            tabs-->
            <div class="form-horizontal">
                <?php echo JHtml::_('bootstrap.startTabSet', 'speedTab', array('active' => 'url_include')); ?>

                <?php echo JHtml::_('bootstrap.addTab', 'speedTab', 'url_include', JText::_('COM_SPEEDCACHE_URL_INCLUDE_TAB')); ?>
                    <?php echo $this->loadTemplate('url_include'); ?>
                <?php echo JHtml::_('bootstrap.endTab'); ?>

                <?php echo JHtml::_('bootstrap.addTab', 'speedTab', 'include_rules', JText::_('COM_SPEEDCACHE_INCLUDE_RULES_TAB')); ?>
                    <?php echo $this->loadTemplate('rule_include'); ?>
                <?php echo JHtml::_('bootstrap.endTab'); ?>

                <?php echo JHtml::_('bootstrap.addTab', 'speedTab', 'url_exclude', JText::_('COM_SPEEDCACHE_URL_EXCLUDE_TAB')); ?>
                    <?php echo $this->loadTemplate('url_exclude'); ?>
                <?php echo JHtml::_('bootstrap.endTab'); ?>

                <?php echo JHtml::_('bootstrap.addTab', 'speedTab', 'exclude_rules', JText::_('COM_SPEEDCACHE_EXCLUDE_RULES_TAB')); ?>
                     <?php echo $this->loadTemplate('rule_exclude'); ?>
                <?php echo JHtml::_('bootstrap.endTab'); ?>
                <?php echo JHtml::_('bootstrap.endTabSet'); ?>
            </div>
            <div>
                <input type="hidden" name="task" value=""/>
                <input type="hidden" name="boxchecked" value="0"/>
                <input type="hidden" name="filter_order" value="<?php echo $this->listOrder; ?>"/>
                <input type="hidden" name="filter_order_Dir" value="<?php echo $this->listDirn; ?>"/>
                <?php echo JHtml::_('form.token'); ?>
            </div>
        </div>
</form>
