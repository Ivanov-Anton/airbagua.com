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
<?php if (empty($this->items)) : ?>
    <div class="alert alert-no-items">
        <?php echo JText::_('JGLOBAL_NO_MATCHING_RESULTS'); ?>
    </div>
<?php else : ?>
    <table class="table table-striped">
        <thead>
        <tr>
            <th width="20" class="center">
                <?php echo JHtml::_('grid.checkall', 'checkall-toggle', 'JGLOBAL_CHECK_ALL', 'joomla_sc.checkAll(this, `url_include`)'); ?>
            </th>
            <th class="title">
                <?php echo JHtml::_('grid.sort', JText::_('COM_SPEEDCACHE_TABLE_URL'), 'a.url', $this->listDirn, $this->listOrder); ?>
            </th>
            <th width="13%" class="center">
                <?php echo JHtml::_('grid.sort', JText::_('COM_SPEEDCACHE_TABLE_CACHE_GUEST'), 'a.cacheguest', $this->listDirn, $this->listOrder); ?>
            </th>
            <th width="13%" class="center">
                <?php echo JHtml::_('grid.sort', JText::_('COM_SPEEDCACHE_TABLE_PRELOAD_GUEST'), 'a.preloadguest', $this->listDirn, $this->listOrder); ?>
            </th>
            <th width="13%" class="center">
                <?php echo JHtml::_('grid.sort', JText::_('COM_SPEEDCACHE_TABLE_CACHE_LOGGED'), 'a.cachelogged', $this->listDirn, $this->listOrder); ?>
            </th>
            <th width="13%" class="center">
                <?php echo JHtml::_('grid.sort', JText::_('COM_SPEEDCACHE_TABLE_PRELOAD_LOGGED'), 'a.preloadlogged', $this->listDirn, $this->listOrder); ?>
            </th>
            <th width="13%" class="center">
                <?php echo JHtml::_('grid.sort', JText::_('COM_SPEEDCACHE_TABLE_PRELOAD_PER_USER'), 'a.preloadperuser', $this->listDirn, $this->listOrder); ?>
            </th>
        </tr>
        </thead>
        <tfoot>
        <tr>
            <td colspan="6">
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
            $canChange = $this->user->authorise('core.edit.state', 'com_speedcache');
            ?>
            <tr class="row<?php echo $i % 2; ?>">
                <td class="center">
                    <?php echo JHtml::_('grid.id', $i, $item->id, false, 'cid', 'url_include'); ?>
                </td>
                <td>
                    <a href="<?php echo JRoute::_('index.php?option=com_speedcache&task=url.edit&id=' . (int)$item->id); ?>">
                        <?php echo $this->escape(JUri::root().$item->url); ?></a>
                </td>
                <td class="center">
                    <?php echo JHtml::_('speedcache.statusCacheGuest', $i, $item->cacheguest, $canChange, 1); ?>
                </td>
                <td class="center">
                    <?php echo JHtml::_('speedcache.statusPreloadGuest', $i, $item->preloadguest, $canChange, 1,$item->cacheguest); ?>
                </td>
                <td class="center">
                    <?php echo JHtml::_('speedcache.statusCacheLogged', $i, $item->cachelogged, $canChange, 1); ?>
                </td>
                <td class="center">
                    <?php echo JHtml::_('speedcache.statusPreloadLogged', $i, $item->preloadlogged, $canChange, 1,$item->cachelogged); ?>
                </td>
                <td class="center">
                    <?php echo JHtml::_('speedcache.statusPreloadPerUser', $i, $item->preloadperuser, $canChange, 1,$item->preloadlogged); ?>
                </td>
            </tr>
            <?php
        endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>