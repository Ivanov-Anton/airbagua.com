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


$curlAvailable = function_exists('curl_init');
if ($curlAvailable) {
    JFactory::getDocument()->addScript(JUri::base() . 'components/com_speedcache/assets/js/dashboard.js');
}
$componentConfig = JComponentHelper::getParams('com_speedcache');
?>
<script>
    token = "<?php echo JSession::getFormToken(); ?>";
</script>
<div id="dashboard">

    <?php
    $session = JFactory::getSession();
    $message = $session->get('speedache_message');
    $session->clear('speedache_message');
    if ($message) :
        ?>
        <div class="message message-<?php echo $message['type']; ?>">
            <div class="alert-message"><?php echo $message['message']; ?></div>
        </div>
    <?php endif;

    //Check if urls have already been added
    if (!$this->urlsCount) : ?>
        <div class="message message-error">
            <div class="alert-message"><?php echo JText::_('COM_SPEEDCACHE_DASHBOARD_NO_URL'); ?></div>
        </div>
    <?php endif; ?>
    <div class="buttons_links">
        <div class="block urls_btn">
            <a href="index.php?option=com_speedcache&view=urls">
                <div class="content">
                    <h3><?php echo JText::_('COM_SPEEDCACHE_DASHBOARD_URLS_BTN'); ?></h3>
                    <div class="block_content">
                        <i class="material-icons">list</i>
                    </div>
                </div>
            </a>
        </div>

        <div class="block file_minification_btn">
            <a href="index.php?option=com_speedcache&view=minifications">
                <div class="content">
                    <h3><?php echo JText::_('COM_SPEEDCACHE_DASHBOARD_FILEMINIFICATION_BTN'); ?></h3>
                    <div class="block_content">
                        <i class="material-icons">code</i>
                    </div>
                </div>
            </a>
        </div>

        <div class="block cdn_btn">
            <a href="index.php?option=com_speedcache&view=cdns">
                <div class="content">
                    <h3><?php echo JText::_('COM_SPEEDCACHE_CONFIG_CDN'); ?></h3>
                    <div class="block_content">
                        <i class="material-icons">cloud_upload</i>
                    </div>
                </div>
            </a>
        </div>

        <div class="block clear_btn">
            <a href="index.php?option=com_speedcache&task=clear&return=<?php echo
            base64_encode('index.php?option=com_speedcache'); ?>&time=<?php echo
            time(); ?>">
                <div class="content">
                    <h3><?php echo JText::_('COM_SPEEDCACHE_DASHBOARD_CLEAR_BTN'); ?></h3>
                    <div class="block_content">
                        <i class="material-icons">flash_on</i>
                    </div>
                </div>
            </a>
        </div>

        <div class="block config_btn">
            <a href="index.php?option=com_config&view=component&component=com_speedcache=&return=<?php echo
            base64_encode('index.php?option=com_speedcache'); ?>">
                <div class="content">
                    <h3><?php echo JText::_('COM_SPEEDCACHE_DASHBOARD_CONFIG_BTN'); ?></h3>
                    <div class="block_content">
                        <i class="material-icons">settings</i>
                    </div>
                </div>
            </a>
        </div>
        <div class="clear"></div>
    </div>


    <!-- Joomla default cache -->
    <?php
    $joomlaCaching = JFactory::getConfig()->get('caching');
    ?>
    <div class="block caching <?php echo ($joomlaCaching) ? 'ok' : 'error'; ?>">
        <div class="content">
            <h3><?php echo JText::_('COM_SPEEDCACHE_DASHBOARD_CACHE_TITLE'); ?></h3>
            <div class="block_content">
                <?php
                if ($joomlaCaching) {
                    echo JText::sprintf('COM_SPEEDCACHE_DASHBOARD_CACHE_ENABLED');
                } else {
                    echo JText::sprintf('COM_SPEEDCACHE_DASHBOARD_CACHE_DISABLED');
                    ?>
                    <div class="button-wrapper">
                        <a class="material-button patch-it"
                           href="#"><?php echo JText::_('COM_SPEEDCACHE_DASHBOARD_PATCH'); ?></a>
                    </div>
                    <?php
                }
                ?>
            </div>
        </div>
    </div>

    <!-- Joomla cache time -->
    <?php
    $recommendedCachetime = 30;
    $joomlaCachetime = JFactory::getConfig()->get('cachetime');
    $joomlaCachetimeGood = $joomlaCachetime >= $recommendedCachetime;
    ?>
    <div class="block cache_time <?php echo ($joomlaCachetimeGood) ? 'ok' : 'error'; ?>">
        <div class="content">
            <h3><?php echo JText::_('COM_SPEEDCACHE_DASHBOARD_CACHETIME_TITLE'); ?></h3>
            <div class="block_content">
                <?php
                if ($joomlaCachetimeGood) {
                    echo JText::sprintf(
                        'COM_SPEEDCACHE_DASHBOARD_CACHETIME_ENABLED',
                        $joomlaCachetime,
                        $recommendedCachetime
                    );
                } else {
                    echo JText::sprintf(
                        'COM_SPEEDCACHE_DASHBOARD_CACHETIME_DISABLED',
                        $joomlaCachetime,
                        $recommendedCachetime
                    );
                    ?>
                    <div class="button-wrapper">
                        <a class="material-button patch-it"
                           href="#"><?php echo JText::_('COM_SPEEDCACHE_DASHBOARD_PATCH'); ?></a>
                    </div>
                    <?php
                }
                ?>
            </div>
        </div>
    </div>

    <!-- Gzip -->
    <div class="block gzip">
        <div class="content">
            <h3><?php echo JText::_('COM_SPEEDCACHE_DASHBOARD_GZIP_TITLE'); ?></h3>
            <div class="block_content">
                <?php if (!$curlAvailable) : ?>
                    <?php echo JText::_('COM_SPEEDCACHE_DASHBOARD_GZIP_CURL_NOTAVAILABLE'); ?>
                <?php else : ?>
                    <div class="checking">
                        <?php echo JText::_('COM_SPEEDCACHE_DASHBOARD_GZIP_CHECKING'); ?>
                    </div>
                    <div class="hidden enabled">
                        <?php echo JText::_('COM_SPEEDCACHE_DASHBOARD_GZIP_ENABLED'); ?>
                    </div>
                    <div class="hidden disabled">
                        <?php echo JText::_('COM_SPEEDCACHE_DASHBOARD_GZIP_DISABLED'); ?>
                        <div class="button-wrapper">
                            <a class="material-button patch-it"
                               href="#"><?php echo JText::_('COM_SPEEDCACHE_DASHBOARD_PATCH'); ?></a>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="clear"></div>

    <!-- if one file is minified turn it to green-->
    <div class="block minify <?php echo $this->checkMinify ? 'ok' : 'error'; ?>">
        <div class="content">
            <h3><?php echo JText::_('COM_SPEEDCACHE_DASHBOARD_CHECK_MINIFY_TITLE'); ?></h3>
            <div class="block_content">
                <?php
                if ($this->checkMinify) : ?>
                    <?php echo JText::sprintf('COM_SPEEDCACHE_DASHBOARD_CHECK_MINIFY_ENABLED'); ?>
                <?php else : ?>
                    <?php echo JText::sprintf('COM_SPEEDCACHE_DASHBOARD_CHECK_MINIFY_DISABLED'); ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!--  if one type of group is activated turn it to green-->
    <?php
    $checkGroupMinify = false;
    if ($componentConfig->get('minify_group_css', 0) ||
        $componentConfig->get('minify_group_js', 0) ||
        $componentConfig->get('minify_group_fonts', 0)
    ) {
        $checkGroupMinify = true;
    }
    ?>
    <div class="block group_minify <?php echo $checkGroupMinify ? 'ok' : 'error'; ?>">
        <div class="content">
            <h3><?php echo JText::_('COM_SPEEDCACHE_DASHBOARD_CHECK_GROUP_MINIFY_TITLE'); ?></h3>
            <div class="block_content">
                <?php
                if ($checkGroupMinify) : ?>
                    <?php echo JText::sprintf('COM_SPEEDCACHE_DASHBOARD_CHECK_GROUP_MINIFY_ENABLED'); ?>
                <?php else : ?>
                    <?php echo JText::sprintf('COM_SPEEDCACHE_DASHBOARD_CHECK_GROUP_MINIFY_DISABLED'); ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- if one CDN info is filled turn it to green-->
    <?php
    $checkCDN = false;
    if ($componentConfig->get('cdn_active', 0) ||
        $componentConfig->get('cdn_url', '') ||
        $componentConfig->get('cdn_content', '') ||
        $componentConfig->get('cdn_exclude_content', '') ||
        $componentConfig->get('cdn_relative_path', 0)
    ) {
        $checkCDN = true;
    }
    ?>
    <div class="block cdn <?php echo $checkCDN ? 'ok' : 'error'; ?>">
        <div class="content">
            <h3><?php echo JText::_('COM_SPEEDCACHE_DASHBOARD_CHECK_CDN_TITLE'); ?></h3>
            <div class="block_content">
                <?php
                if ($checkCDN) : ?>
                    <?php echo JText::sprintf('COM_SPEEDCACHE_DASHBOARD_CHECK_CDN_ENABLED'); ?>
                <?php else : ?>
                    <?php echo JText::sprintf('COM_SPEEDCACHE_DASHBOARD_CHECK_CDN_DISABLED'); ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="clear"></div>

    <!-- Expires headers -->
    <div class="block expires">
        <div class="content">
            <h3><?php echo JText::_('COM_SPEEDCACHE_DASHBOARD_EXPIRESHEADERS_TITLE'); ?></h3>
            <div class="block_content">
                <?php if (!$curlAvailable) : ?>
                    <?php echo JText::_('COM_SPEEDCACHE_DASHBOARD_EXPIRESHEADERS_CURL_NOTAVAILABLE'); ?>
                <?php else : ?>
                    <div class="checking">
                        <?php echo JText::_('COM_SPEEDCACHE_DASHBOARD_EXPIRESHEADERS_CHECKING'); ?>
                    </div>
                    <div class="hidden enabled">
                        <?php echo JText::_('COM_SPEEDCACHE_DASHBOARD_EXPIRESHEADERS_ENABLED'); ?>
                    </div>
                    <div class="hidden disabled">
                        <?php echo JText::_('COM_SPEEDCACHE_DASHBOARD_EXPIRESHEADERS_DISABLED'); ?>
                        <?php if (file_exists(JPATH_ROOT . DIRECTORY_SEPARATOR . '.htaccess') &&
                            is_writable(JPATH_ROOT . DIRECTORY_SEPARATOR . '.htaccess')) : ?>
                            <div class="button-wrapper">
                                <a class="material-button patch-it"
                                   href="#"><?php echo JText::_('COM_SPEEDCACHE_DASHBOARD_PATCH'); ?></a>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>

        </div>
    </div>

    <!-- Expires headers time -->
    <div class="block expires_time">
        <div class="content">
            <h3><?php echo JText::_('COM_SPEEDCACHE_DASHBOARD_EXPIRESHEADERS_TIME_TITLE'); ?></h3>
            <div class="block_content">
                <?php if (!$curlAvailable) : ?>
                    <?php echo JText::_('COM_SPEEDCACHE_DASHBOARD_EXPIRESHEADERS_CURL_NOTAVAILABLE'); ?>
                <?php else : ?>
                    <div class="checking">
                        <?php echo JText::_('COM_SPEEDCACHE_DASHBOARD_EXPIRESHEADERS_TIME_CHECKING'); ?>
                    </div>
                    <div class="hidden missing">
                        <?php echo JText::_('COM_SPEEDCACHE_DASHBOARD_EXPIRESHEADERS_TIME_MISSING'); ?>
                    </div>
                    <div class="hidden enabled">
                        <?php echo JText::_('COM_SPEEDCACHE_DASHBOARD_EXPIRESHEADERS_TIME_ENABLED'); ?>
                    </div>
                    <div class="hidden disabled">
                        <?php echo JText::_('COM_SPEEDCACHE_DASHBOARD_EXPIRESHEADERS_TIME_DISABLED'); ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Php version -->
    <?php
    $phpversion = phpversion();
    ?>
    <div class="block php <?php echo ($phpversion[0] == '7') ? 'ok' : 'error'; ?>">
        <div class="content">
            <h3><?php echo JText::_('COM_SPEEDCACHE_DASHBOARD_PHP_VERSION_TITLE'); ?></h3>
            <div class="block_content">
                <?php
                if ($phpversion[0] < 7) : ?>
                    <?php echo JText::sprintf('COM_SPEEDCACHE_DASHBOARD_PHP_VERSION_DISABLED', $phpversion); ?>
                <?php else : ?>
                    <?php echo JText::sprintf('COM_SPEEDCACHE_DASHBOARD_PHP_VERSION_ENABLED', $phpversion); ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="clear"></div>

    <!-- Speedcache browser cache -->
    <?php
    $browserCache = $componentConfig->get('use_browser_cache', false);
    ?>
    <div class="block browser_cache <?php echo $browserCache ? 'ok' : 'error'; ?>">
        <div class="content">
            <h3><?php echo JText::_('COM_SPEEDCACHE_DASHBOARD_BROWSER_CACHE_TITLE'); ?></h3>
            <div class="block_content">
                <?php
                if ($browserCache) : ?>
                    <?php echo JText::sprintf('COM_SPEEDCACHE_DASHBOARD_BROWSER_CACHE_ENABLED'); ?>
                <?php else : ?>
                    <?php echo JText::sprintf('COM_SPEEDCACHE_DASHBOARD_BROWSER_CACHE_DISABLED'); ?>
                    <div class="button-wrapper">
                        <a class="material-button patch-it"
                           href="#"><?php echo JText::_('COM_SPEEDCACHE_DASHBOARD_PATCH'); ?></a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Speedcache auto clear-->
    <?php
    $autoclear = $componentConfig->get('clear_on_admin_tasks', true);
    ?>
    <div class="block autoclear <?php echo $autoclear ? 'ok' : 'error'; ?>">
        <div class="content">
            <h3><?php echo JText::_('COM_SPEEDCACHE_DASHBOARD_AUTO_CLEAR_CACHE_TITLE'); ?></h3>
            <div class="block_content">
                <?php
                if ($autoclear) : ?>
                    <?php echo JText::sprintf('COM_SPEEDCACHE_DASHBOARD_AUTO_CLEAR_CACHE_ENABLED'); ?>
                <?php else : ?>
                    <?php echo JText::sprintf('COM_SPEEDCACHE_DASHBOARD_AUTO_CLEAR_CACHE_DISABLED'); ?>
                    <div class="button-wrapper">
                        <a class="material-button patch-it"
                           href="#"><?php echo JText::_('COM_SPEEDCACHE_DASHBOARD_PATCH'); ?></a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Check expires modules on server-->
    <div class="block expired_module">
        <div class="content">
            <h3><?php echo JText::_('COM_SPEEDCACHE_DASHBOARD_EXPIRES_MODULE_TITLE'); ?></h3>
            <div class="block_content">
                <div class="checking">
                    <?php echo JText::_('COM_SPEEDCACHE_DASHBOARD_EXPIRES_MODULE_CHECKING'); ?>
                </div>
                <div class="hidden enabled">
                    <?php echo JText::sprintf('COM_SPEEDCACHE_DASHBOARD_EXPIRES_MODULE_ENABLED'); ?>
                </div>
                <div class="hidden disabled">
                    <?php echo JText::sprintf('COM_SPEEDCACHE_DASHBOARD_EXPIRES_MODULE_DISABLED'); ?>
                </div>
            </div>
        </div>
    </div>

    <div class="clear"></div>

</div>