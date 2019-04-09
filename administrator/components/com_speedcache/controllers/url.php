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
 * speedcache Component speedcache Model
 *
 * @since  1.6
 */
class speedcacheControllerUrl extends JControllerForm
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

    public function save($key = null, $urlVar = null)
    {
        $data  = $this->input->post->get('jform', array(), 'array');
        $config = JComponentHelper::getParams('com_speedcache');
        $model = $this->getModel();
        if(empty($data['url']) ) {
            $data['url'] = "/";
        }
        if(isset($data['cacheguest'])) {
            if ($data['cacheguest'] == '0') {
                $data['preloadguest'] = 0;
            }
        }
        if(isset($data['cachelogged'])) {
            if ($data['cachelogged'] == '0') {
                $data['preloadlogged'] = 0;
                $data['preloadperuser'] = 0;
            }
        }

        if(isset($data['preloadlogged'])){
            if($data['preloadlogged'] == '0'){
                $data['preloadperuser'] = 0;
            }
        }
        if(isset($data['lifetime'])){
            if($data['lifetime'] == '1'){
                $data['specifictime'] = $config->get('cache_lifetime', 1440);
            }
        }

        $this->input->post->set('jform',$data, 'array');

        if ($model->urlExists(trim($data['url'], '/')) && empty($data['id']) ) {
            $this->setError(JText::_('COM_SPEEDCACHE_TABLE_URL_ALREADY_WARNING'));
            $this->setMessage($this->getError(), 'error');

            $this->setRedirect(
                JRoute::_(
                    'index.php?option=' . $this->option . '&view=' . $this->view_list
                    . $this->getRedirectToListAppend(), false
                )
            );
            return false;
        }
        return parent::save($key, $urlVar);
    }

    /*
     * Add multiple url add once from menu popup
     */
    public function saveMany()
    {
        //Check token
        if (!JSession::checkToken('GET')) {
            echo json_encode(array('status' => 'error'));
            jexit();
        }

        $app = JFactory::getApplication();

        //Set POST request token for save loop
        $app->input->post->set(JSession::getFormToken(), '1');


        $model = $this->getModel();
        $items = $app->input->get('items', array(), 'raw');
        $this->task = 'save';
        $count = 0;

        if(!empty($items)){
            foreach ($items as $item) {
                $path = '';
                if(strpos($item,Juri::root()) !== false){
                    $path = substr($item,strlen(Juri::root()));
                }
                //Check if the url is already in database
                if (!$model->urlExists(trim($path, '/'))) {
                    $data = array('url' => $path, 'cacheguest' => 1, 'preloadguest' => 0, 'cachelogged' =>1, 'preloadlogged' => 0, 'preloadperuser' => 0,'lifetime' => 1,'specifictime' => 0,'ignoreparams' => 0,'excludeguest' => 0, 'excludelogged' => 0, 'type' => 'include');
                    $app->input->post->set('jform', $data);
                    if ($this->save()) {
                        $count++;
                    }
                }
            }
        }
        JFactory::getSession()->set('speedache_message', array('type' => 'message', 'message' => JText::sprintf('COM_SPEEDCACHE_MENUS_X_URL_PROCESSED', $count, count($items) - $count)));
        echo json_encode((array('status' => 'ok', 'processed' => $count, 'skipped' => count($items) - $count)));
        jexit();
    }

    /*
     * Add multiple url add once from menu popup
     */
    public function saveUrlExclude()
    {
        //Check token
        if (!JSession::checkToken('GET')) {
            echo json_encode(array('status' => 'error'));
            jexit();
        }
        $app = JFactory::getApplication();

        //Set POST request token for save loop
        $app->input->post->set(JSession::getFormToken(), '1');


        $model = $this->getModel();
        $items = $app->input->get('items', array(), 'raw');
        $this->task = 'save';
        $count = 0;
        if(!empty($items)){
            foreach ($items as $item) {
                $path = '';
                if(strpos($item,Juri::root()) !== false){
                    $path = substr($item,strlen(Juri::root()));
                }
                //Check if the url is already in database
                if (!$model->urlExists(trim($path, '/'))) {
                    $data = array('url' => $path, 'cacheguest' => null, 'preloadguest' => null, 'cachelogged' =>null, 'preloadlogged' => null,'preloadperuser' => null,'lifetime' => null,'specifictime' => null,'ignoreparams' => null, 'excludeguest' => 1, 'excludelogged' => 1,'type' => 'exclude');
                    $app->input->post->set('jform', $data);
                    if ($this->save()) {
                        $count++;
                    }
                }
            }
        }

        JFactory::getSession()->set('speedache_message', array('type' => 'message', 'message' => JText::sprintf('COM_SPEEDCACHE_MENUS_X_URL_PROCESSED', $count, count($items) - $count)));
        echo json_encode((array('status' => 'ok', 'processed' => $count, 'skipped' => count($items) - $count)));
        jexit();
    }
    //redirect to add include rules cache page
    public function addincludeRulesItems(){
        $this->redirectTab('rules_include');
    }
    //redirect to add exclude cache page
    public function addexcludeItems(){
        $this->redirectTab('exclude');
    }
    //redirect to add exclude cache page
    public function addexcludeRulesItems(){
        $this->redirectTab('rules_exclude');
    }
    //save to includerules
    public function saveincludeRules(){
        $this->saveUrlSC('rules_include',true);
    }
    //save exclude to database
    public function saveexclude(){
        $this->saveUrlSC('exclude',true);
    }
    //save exclude to database
    public function saveexcludeRules(){
        $this->saveUrlSC('rules_exclude',true);
    }
    public function applyincludeRules(){
        $this->saveUrlSC('rules_include');
    }
    public function applyexclude(){
        $this->saveUrlSC('exclude');
    }
    public function applyexcludeRules(){
        $this->saveUrlSC('rules_exclude');
    }
    //save parameter to databe per tab
    public function saveUrlSC($type,$direct = false){
        $model = $this->getModel();
        $data  = $this->input->post->get('jform', array(), 'array');

        if(empty($data['url']) ) {
            $data['url'] = "/";
        }
        $config = JComponentHelper::getParams('com_speedcache');
        if(isset($data['lifetime'])){
            if($data['lifetime'] == '1'){
                $data['specifictime'] = $config->get('cache_lifetime', 1440);
            }
        }

        if($direct){
            $this->task = 'save';
        }else{
            $this->task = 'apply';
        }

        if(!empty($data)){
            switch ($type){
                case 'rules_include':
                    $data = array('url' => $data['url'], 'cacheguest' => $data['cacheguest'], 'preloadguest' => 0, 'cachelogged' => $data['cachelogged'], 'preloadlogged' => 0, 'preloadperuser' => 0, 'lifetime' => $data['lifetime'],'specifictime' => $data['specifictime'], 'ignoreparams' => (int)$data['ignoreparams'], 'excludeguest' => 0, 'excludelogged' => 0,'type' => 'rules_include');
                break;
                case 'exclude':
                    $data = array('url' => $data['url'], 'cacheguest' => 0, 'preloadguest' => 0, 'cachelogged' => 0, 'preloadlogged' => 0,'preloadperuser' => 0,'lifetime' => 0,'specifictime' => 0,'ignoreparams' => 0, 'excludeguest' => (int)$data['excludeguest'], 'excludelogged' => (int)$data['excludelogged'],'type' => 'exclude');
                break;
                default:
                    $data = array('url' => $data['url'], 'cacheguest' => 0, 'preloadguest' => 0, 'cachelogged' => 0, 'preloadlogged' => 0,'preloadperuser' => 0, 'lifetime' => 0,'specifictime' => 0,'ignoreparams' => 0, 'excludeguest' => (int)$data['excludeguest'], 'excludelogged' => (int)$data['excludelogged'],'type' => 'rules_exclude');
            }
        }

        if ($model->urlExists(trim($data['url'], '/'))) {
            $this->setError(JText::_('COM_SPEEDCACHE_TABLE_URL_ALREADY_WARNING'));
            $this->setMessage($this->getError(), 'error');

            $this->setRedirect(
                JRoute::_(
                    'index.php?option=' . $this->option . '&view=' . $this->view_list
                    . $this->getRedirectToListAppend(), false
                )
            );
            return false;
        }
        $this->input->post->set('jform', $data, 'array');;
        return parent::save();
    }
    //set redirect per tab
    public function redirectTab($tab){
        $context = "$this->option.edit.$this->context";
        // Access check.
        if (!$this->allowAdd())
        {
            // Set the internal error and also the redirect error.
            $this->setError(JText::_('JLIB_APPLICATION_ERROR_CREATE_RECORD_NOT_PERMITTED'));
            $this->setMessage($this->getError(), 'error');

            $this->setRedirect(
                JRoute::_(
                    'index.php?option=' . $this->option . '&view=' . $this->view_list
                    . $this->getRedirectToListAppend(), false
                )
            );

            return false;
        }

        // Clear the record edit information from the session.
        JFactory::getApplication()->setUserState($context . '.data', null);

        switch($tab){
            case 'rules_include':
                $layout = 'addincluderulesitems';
                break;
            case 'exclude':
                $layout = 'addexcludeitems';
                break;
            default :
                $layout = 'addexcluderulesitems';
        }
        // Redirect to the edit screen.
        $this->setRedirect(
            JRoute::_(
                'index.php?option=' . $this->option . '&view=' . $this->view_item
                . '&layout='.$layout, false
            )
        );

        return true;
    }
}
