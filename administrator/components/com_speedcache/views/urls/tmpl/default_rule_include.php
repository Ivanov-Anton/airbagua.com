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
?>
<?php if (empty($this->cacheitems)) : ?>
    <div class="alert alert-no-items">
        <?php echo JText::_('JGLOBAL_NO_MATCHING_RESULTS'); ?>
    </div>
<?php else : ?>
    <table class="table table-striped">
        <thead>
        <tr>
            <th width="20" class="center">
                <?php echo JHtml::_('grid.checkall', 'checkall-toggle', 'JGLOBAL_CHECK_ALL', 'joomla_sc.checkAll(this , `rules_include`)'); ?>
            </th>
            <th class="title">
                <?php echo JHtml::_('grid.sort', JText::_('COM_SPEEDCACHE_TABLE_URL'), 'a.url', $this->listDirn, $this->listOrder); ?>
            </th>
            <th width="13%" class="center">
                <?php echo JHtml::_('grid.sort', JText::_('COM_SPEEDCACHE_TABLE_IGNORE_PARAMS'), 'a.ignoreparams', $this->listDirn, $this->listOrder); ?>
            </th>
            <th width="13%" class="center">
                <?php echo JHtml::_('grid.sort', JText::_('COM_SPEEDCACHE_TABLE_CACHE_GUEST'), 'a.cacheguest', $this->listDirn, $this->listOrder); ?>
            </th>
            <th width="13%" class="center">
                <?php echo JHtml::_('grid.sort', JText::_('COM_SPEEDCACHE_TABLE_CACHE_LOGGED'), 'a.cachelogged', $this->listDirn, $this->listOrder); ?>
            </th>
        </tr>
        </thead>
        <tfoot>
        <tr>
            <td colspan="6">
                <!--                                pagination-->
            </td>
        </tr>
        </tfoot>
        <tbody>
        <?php foreach ($this->cacheitems as $i => $item) :
            if ($item->type == 'rules_include'):
                $canChange = $this->user->authorise('core.edit.state', 'com_speedcache');
                ?>
                <tr class="row<?php echo $i % 2; ?>">
                    <td class="center">
                        <?php echo JHtml::_('grid.id', $i, $item->id, false, 'cid', 'rules_include'); ?>
                    </td>
                    <td>
                        <a href="<?php echo JRoute::_('index.php?option=com_speedcache&task=url.edit&id=' . (int)$item->id); ?>">
                            <?php echo $this->escape(JUri::root().$item->url); ?></a>
                    </td>
                    <td class="center">
                        <?php echo JHtml::_('speedcache.statusIgnoreParams', $i, $item->ignoreparams, $canChange, 2); ?>
                    </td>
                    <td class="center">
                        <?php echo JHtml::_('speedcache.statusCacheGuest', $i, $item->cacheguest, $canChange, 2); ?>
                    </td>
                    <td class="center">
                        <?php echo JHtml::_('speedcache.statusCacheLogged', $i, $item->cachelogged, $canChange, 2); ?>
                    </td>
                </tr>
                <?php
            endif;
        endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>