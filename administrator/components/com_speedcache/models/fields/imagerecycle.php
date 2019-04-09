<?php
/**
 * Speedcache
 *
 * We developed this code with our hearts and passion.
 * We hope you found it useful, easy to understand and to customize.
 * Otherwise, please feel free to contact us at contact@joomunited.com *
 * @package Speedcache
 * @copyright Copyright (C) 2013 JoomUnited (http://www.joomunited.com). All rights reserved.
 * @copyright Copyright (C) 2013 Damien Barr?re (http://www.crac-design.com). All rights reserved.
 * @license GNU General Public License version 2 or later; http://www.gnu.org/licenses/gpl-2.0.html
 */

defined('_JEXEC') or die;

jimport('joomla.form.formfield');

/**
 * Form Field class for the Joomla Framework.
 */
class JFormFieldImagerecycle extends JFormField
{

    /**
     * Image recycle input
     */
    protected function getInput()
    {
        $html = '';
        $is_enabled = JComponentHelper::isInstalled('com_imagerecycle');
        if ($is_enabled) {
            //extension installed
            $html = '<div class="main-presentation"
                 style="margin: 0px auto; max-width: 1200px; background-color:#f0f1f4;font-family: helvetica,arial,sans-      serif;">
                <div class="main-textcontent"
                     style="margin: 0px auto; border-left: 0px dotted #d2d3d5; border-right: 0px dotted #d2d3d5; width:          840px; background-color:#fff;border-top: 5px solid #544766;"
                     cellspacing="0" cellpadding="0" align="center">

                    <a href="https://www.imagerecycle.com/" target="_blank"> <img
                            src="https://www.imagerecycle.com/images/Notification-mail/logo-image-recycle.png"
                            alt="logo image recycle" class="CToWUd"
                            style="display: block; outline: medium none; text-decoration: none; margin-left: auto; margin-right: auto; margin-top:15px;"
                            height="84" width="500"> </a>

                    <p style="background-color: #ffffff; color: #445566; font-family: helvetica,arial,sans-serif; font-size: 24px; line-height: 24px; padding-right: 10px; padding-left: 10px;"
                       align="center"><strong>'.JText::_('COM_SPEEDCACHE_CONFIG_IMAGE_RECYCLE_WELCOME').'<br/><br/></strong>
                    </p>

                    <a style="width: 200px; height: 35px; background: #554766; font-size: 12px; line-height: 18px; text-align: center; margin-right:4px;color: #fff;font-size: 14px;text-decoration: none; text-transform: uppercase; padding: 8px 20px;font-weight:bold;"
                       href="index.php?option=com_imagerecycle">'.JText::_('COM_SPEEDCACHE_CONFIG_IMAGE_RECYCLE_GOTO').'</a>
                    <p><br/><br/></p>
                </div>
            </div>';
        } else {
            $html = '<div class="main-presentation" style="margin: 0px auto; max-width: 1200px; background-color:#f0f1f4;font-family: helvetica,arial,sans-serif;">
                    <div class="main-textcontent" style="margin: 0px auto; min-height: 400px; border-left: 1px dotted #d2d3d5; border-right: 1px dotted #d2d3d5; width: 840px; background-color:#fff;border-top: 5px solid #544766;" cellspacing="0" cellpadding="0" align="center">
                        <a href="https://www.imagerecycle.com/" target="_blank"> <img src="https://www.imagerecycle.com/images/Notification-mail/logo-image-recycle.png" alt="logo image recycle" width="500" height="84" style="display: block; outline: medium none; text-decoration: none; margin-left: auto; margin-right: auto; margin-top:15px;"> </a>
                        <p style="background-color: #ffffff; color: #445566; font-family: helvetica,arial,sans-serif; font-size: 24px; line-height: 24px; padding-right: 10px; padding-left: 10px;" align="center"><strong>Get faster with lightweight images!<br></strong></p>
                        <p style="background-color: #ffffff; color: #445566; font-family: helvetica,arial,sans-serif; font-size: 14px; line-height: 22px; padding:20px; text-align: center;"> <strong>Speed optimization of your Joomla website is highly recommended for SEO. The image compression is one of the tools that help to reduce your page size significantly while preserving the image quality.<br><img src="https://www.imagerecycle.com/images/No-compress/Optimization.gif" alt="optimization" style="display: block; outline: medium none; text-decoration: none; margin-left: auto; margin-right: auto; margin-top:15px;"><br>Speed Cache is fully integrated with ImageRecycle service, you have a free trial with no engagement and we provide a 20% OFF coupon! Use the coupon here: <a href="https://www.imagerecycle.com/" target="_blank">www.imagerecycle.com</a></strong> </p>
                        <div style="background-color:#fafafa; border: 2px dashed #ccc; border-left: 5px solid #A1B660; font-size: 30px; padding: 20px; line-height: 40px;">ImageRecycle 20% OFF, apply on all memberships: SPEEDCACHE-20</div>
                        <p style="background-color: #ffffff; color: #445566; font-family: helvetica,arial,sans-serif; font-size: 12px; line-height: 22px; padding-left: 20px; padding-right: 20px; text-align: center;  font-style: italic;">ImageRecycle got a dedicated Joomla extension that runs the images and PDF optimization automatically on your website.
                            <br>In order to start the optimization process, please install the Joomla extension. Enjoy!</p>
                        <p></p>
                        <p><a style="float: right; background: #554766; font-size: 12px; line-height: 18px; text-align: center;color: #fff;font-size: 14px;text-decoration: none; text-transform: uppercase; padding: 8px 30px; font-weight:bold;letter-spacing: 0.8px;box-shadow: 1px 1px 12px #ccc;" target="_blank" class="edit" href="https://www.imagerecycle.com/cms/joomla" aria-label="Install ImageRecycle pdf &amp; image compression 2.1.1 now">Install Joomla extension</a></p>
                    </div>
                  </div>';
        }
        return $html;
    }

}