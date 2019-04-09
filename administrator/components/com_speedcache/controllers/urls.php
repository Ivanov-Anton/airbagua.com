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
 * speedcache list controller class.
 *
 * @since  1.6
 */
class speedcacheControllerUrls extends JControllerAdmin
{
    /**
     * Method to get a model object, loading it if required.
     *
     * @param   string $name The model name. Optional.
     * @param   string $prefix The class prefix. Optional.
     * @param   array $config Configuration array for model. Optional.
     *
     * @return  object  The model.
     *
     * @since   1.6
     */
    public function getModel($name = 'Url', $prefix = 'speedcacheModel', $config = array('ignore_request' => true))
    {
        $model = parent::getModel($name, $prefix, $config);

        return $model;
    }

    public function uncacheGuest()
    {
        $this->changeState('cacheguest', 0,'unpreloadguest');
    }

    public function cacheGuest()
    {
        $this->changeState('cacheguest', 1);
    }

    public function unpreloadGuest()
    {
        $this->changeState('preloadguest', 0);
    }

    public function preloadGuest()
    {
        $this->changeState('preloadguest', 1);
    }

    public function uncacheLogged()
    {
        $this->changeState('cachelogged', 0 , 'unpreloadlogged');
    }

    public function cacheLogged()
    {
        $this->changeState('cachelogged', 1);
    }

    public function unpreloadLogged()
    {
        $this->changeState('preloadlogged', 0 , 'unpreloadperuser');
    }

    public function preloadLogged()
    {
        $this->changeState('preloadlogged', 1);
    }

    public function unpreloadPerUser()
    {
        $this->changeState('preloadperuser', 0);
    }

    public function preloadPerUser()
    {
        $this->changeState('preloadperuser', 1);
    }

    public function unexcludeGuest()
    {
        $this->changeState('excludeguest', 0);
    }

    public function excludeGuest()
    {
        $this->changeState('excludeguest', 1);
    }

    public function unexcludeLogged()
    {
        $this->changeState('excludelogged', 0);
    }

    public function excludeLogged()
    {
        $this->changeState('excludelogged', 1);
    }

    public function ignoreParams()
    {
        $this->changeState('ignoreparams', 1);
    }

    public function unignoreParams()
    {
        $this->changeState('ignoreparams', 0);
    }

    protected function changeState($column, $value ,$type = null)
    {
        if(!empty($type)){
            switch ($type){
                case 'unpreloadguest':
                    $this->unpreloadGuest();
                    break;
                case 'unpreloadlogged':
                    $this->unpreloadLogged();
                    break;
                case 'unpreloadperuser':
                    $this->unpreloadPerUser();
                    break;
            }
        }
        // Check for request forgeries
        JSession::checkToken() or die(JText::_('JINVALID_TOKEN'));

        // Get items to publish from the request.
        $cid = JFactory::getApplication()->input->get('cid', array(), 'array');
        if (empty($cid)) {
            JLog::add(JText::_($this->text_prefix . '_NO_ITEM_SELECTED'), JLog::WARNING, 'jerror');
        } else {
            // Get the model.
            $model = $this->getModel();

            // Make sure the item ids are integers
            JArrayHelper::toInteger($cid);
            // Make sure item ids have enabled cache
            if($column == 'preloadguest'){
                foreach ($cid as $k => $id){
                    $cacheguestId = $model->getCacheValues($id, 'cacheguest');
                    if($cacheguestId == '0'){
                        unset($cid[$k]);
                    }
                }
            } elseif($column == 'preloadlogged'){
                foreach ($cid as $k => $id){
                    $cacheguestId = $model->getCacheValues($id, 'cachelogged');
                    if($cacheguestId == '0'){
                        unset($cid[$k]);
                    }
                }
            } elseif ($column == 'preloadperuser'){
                foreach ($cid as $k => $id){
                    $cacheguestId = $model->getCacheValues($id, 'preloadlogged');
                    if($cacheguestId == '0'){
                        unset($cid[$k]);
                    }
                }
            }
            // Publish the items.
            try {
                $model->changeState($cid, $value, $column);
                $errors = $model->getErrors();

                if ($value == 1) {
                    if ($errors) {
                        $app = JFactory::getApplication();
                        $app->enqueueMessage(JText::plural($this->text_prefix . '_N_ITEMS_FAILED_PUBLISHING', count($cid)), 'error');
                    } else {
                        $ntext = $this->text_prefix . '_N_ITEMS_PUBLISHED';
                    }
                } elseif ($value == 0) {
                    $ntext = $this->text_prefix . '_N_ITEMS_UNPUBLISHED';
                } elseif ($value == 2) {
                    $ntext = $this->text_prefix . '_N_ITEMS_ARCHIVED';
                } else {
                    $ntext = $this->text_prefix . '_N_ITEMS_TRASHED';
                }

                $this->setMessage(JText::plural($ntext, count($cid)));
            } catch (Exception $e) {
                $this->setMessage($e->getMessage(), 'error');
            }
        }

        $extension = $this->input->get('extension');
        $extensionURL = ($extension) ? '&extension=' . $extension : '';
        $this->setRedirect(JRoute::_('index.php?option=' . $this->option . '&view=' . $this->view_list . $extensionURL, false));
    }

}
