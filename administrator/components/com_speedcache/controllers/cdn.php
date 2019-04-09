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

/**
 * speedcache Component speedcache
 *
 * @since  1.6
 */
class speedcacheControllerCdn extends JControllerForm
{
    /**
     * Method (override) to check if you can save a new or existing record.
     *
     * Adjusts for the primary key name and hands off to the parent class.
     *
     * @param   array $data An array of input data.
     * @param   string $key The name of the key for the primary key.
     *
     * @return  boolean
     *
     * @since   1.6
     */
    protected function allowSave($data, $key = 'id')
    {
        return parent::allowSave($data, $key);
    }

    public function save($key = null, $cdn = null)
    {
        $data  = $this->input->post;
        $cdn_content = 'media,templates';
        if ($data->get('cdn_content', null, 'string')) {
            $cdn_content = $data->get('cdn_content', null, 'string');
        }
        $array = array(
            'cdn_active' => $data->get('cdn_active', 0),
            'cdn_url' => $data->get('cdn_url', null, 'string'),
            'cdn_content' =>  $cdn_content,
            'cdn_exclude_content' => $data->get('cdn_exclude_content', null, 'string'),
            'cdn_relative_path' => $data->get('cdn_relative_path', 0)
        );
        if (speedcacheComponentHelper::setParams($array)) {
            JFactory::getSession()->set(
                'speedache_message',
                array(
                    'type' => 'message',
                    'message' => JText::_('COM_SPEEDCACHE_CONFIG_CDN_SUCCESS_MESSAGE')
                )
            );
        } else {
            $this->setError(JText::_('COM_SPEEDCACHE_CONFIG_CDN_ERROR_MESSAGE'));
            $this->setMessage($this->getError(), 'error');
        }
        $this->setRedirect(
            JRoute::_(
                'index.php?option=' . $this->option . '&view=' . $this->view_list
                .$this->getRedirectToListAppend(),
                false
            )
        );
        return true;
    }
}
