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

// Include the HTML helpers.
JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');

JHtml::_('behavior.formvalidator');
JHtml::_('behavior.keepalive');
JHtml::_('formbehavior.chosen', 'select');

JFactory::getDocument()->addScriptDeclaration("
		Joomla.submitbutton = function(task)
		{
			if (task == 'url.cancel' || document.formvalidator.isValid(document.getElementById('speedcache-form')))
			{
				Joomla.submitform(task, document.getElementById('speedcache-form'));
			}
		};
");
?>
<script>
    jQuery('document').ready(function($){
        var option = $('.lifetime select#jform_lifetime').val();
        check(option);
        $('.lifetime select#jform_lifetime').on('change',function(){
            check($(this).val());
        });
        function check(option){
            if(option == '2'){
                $('.specifictime').css('display','inline-block');
            }else{
                $('.specifictime').css('display','none');
            }
        }
    });

</script>
<form action="<?php echo JRoute::_('index.php?option=com_speedcache&layout=edit&id=' . (int)$this->item->id); ?>"
      method="post" name="adminForm" id="speedcache-form" class="form-validate form-horizontal">
    <fieldset class="adminform">

        <div class="control-group">
            <div class="control-label">
                <?php echo $this->form->getLabel('url'); ?>
            </div>
            <div class="controls">
                <?php echo Juri::root().$this->form->getInput('url'); ?>
            </div>
        </div>
        <div class="control-group">
            <div class="control-label">
                <?php echo $this->form->getLabel('ignoreparams'); ?>
            </div>
            <div class="controls">
                <?php echo $this->form->getInput('ignoreparams'); ?>
            </div>
        </div>
        <div class="control-group">
            <div class="control-label">
                <?php echo $this->form->getLabel('cacheguest'); ?>
            </div>
            <div class="controls">
                <?php echo $this->form->getInput('cacheguest'); ?>
            </div>
        </div>

        <div class="control-group">
            <div class="control-label">
                <?php echo $this->form->getLabel('cachelogged'); ?>
            </div>
            <div class="controls">
                <?php echo $this->form->getInput('cachelogged'); ?>
            </div>
        </div>

        <div class="control-group lifetime">
            <div class="control-label">
                <?php echo $this->form->getLabel('lifetime'); ?>
            </div>
            <div class="controls">
                <?php echo $this->form->getInput('lifetime'); ?>
            </div>
        </div>
        <div class="control-group specifictime">
            <div class="control-label">
                <?php echo $this->form->getLabel('specifictime'); ?>
            </div>
            <div class="controls">
                <?php echo $this->form->getInput('specifictime'); ?>
            </div>
        </div>

        <?php echo $this->form->getInput('type'); ?>
        <?php echo $this->form->getInput('id'); ?>
    </fieldset>
    <input type="hidden" name="task" value=""/>
    <?php echo JHtml::_('form.token'); ?>
</form>
