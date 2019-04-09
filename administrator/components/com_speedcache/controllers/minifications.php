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
class speedcacheControllerMinifications extends JControllerAdmin
{
    private $files = array();
    private $allowedPath = array('components/com_content', 'templates');
    private $unallowedPath = array('administrator', 'bin', 'cache', 'cli', 'images', 'language');
    private $allowed_ext = array('js', 'css', 'eot', 'ttf', 'woff', 'otf');

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
    public function getModel(
        $name = 'Minifications',
        $prefix = 'speedcacheModel',
        $config = array('ignore_request' => true)
    ) {
        $model = parent::getModel($name, $prefix, $config);

        return $model;
    }

    /*
     * get all folder of system
     */
    public function getAllowedPath()
    {
        $componentParams = JComponentHelper::getParams('com_speedcache');
        $include_folders = $componentParams->get('include_minify_folders', '');
        if (!empty($include_folders)) {
            if (is_array($include_folders)) {
                $this->allowedPath = $include_folders;
            } else {
                $this->allowedPath = explode(',', $include_folders);
            }
        }

        $scan_dir = array();

        foreach ($this->allowedPath as $dir) {
            //exclude folder
            if (in_array($dir, $this->unallowedPath)) {
                continue;
            }
            $dir = JPATH_ROOT . DIRECTORY_SEPARATOR . $dir;
            $scan_dir[] = $dir;
        }

        $db = JFactory::getDbo();
        $model = $this->getModel();
        $ids = array();

        if ($model->deleteMinifyNotSet()) {
            $items = $model->getMinifyItems();
            if (!empty($items)) {
                foreach ($items as $item) {
                    $dir = JPATH_ROOT . '/' . $item['file'];
                    //check file exist on server
                    if (!file_exists($dir)) {
                        $ids[] = $db->quote($item['id']);
                    }
                }
            }
            if (!empty($ids)) {
                //delete file in database if file not exists on server
                $model->deleteMinifyInID($ids);
            }
        }

        if (empty($scan_dir)) {
            echo json_encode(array('status' => 'false'));
        } else {
            echo json_encode($scan_dir);
        }
        jexit();
    }

    /*
     * get file assest from url
     */
    public function getFileAssets()
    {
        $app = JFactory::getApplication();
        $dir = $app->input->get('dir', '', 'string');

        $this->allowed_ext = array_values($this->allowed_ext);
        $base_dir = JPATH_ROOT;
        $files = array();
        //get file assets in folders
        foreach (new RecursiveIteratorIterator(new IgnorantRecursiveDirectoryIterator($dir)) as $filename) {
            if (!is_file($filename)) {
                continue;
            }
            $data = array();
            $data['filename'] = str_replace('\\', '/', substr($filename, strlen($base_dir) + 1));
            $data['filetype'] = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

            if (!in_array($data['filetype'], $this->allowed_ext)) {
                continue;
            }

            if (strpos($data['filename'], 'min.css') !== false || strpos($data['filename'], '.min.js') !== false) {
                continue;
            }

            $files[] = $data;
        }

        if (empty($files)) {
            echo json_encode(array('status' => 'false'));
            jexit();
        }

        // save file
        $model = $this->getModel();
        $result = array();
        foreach ($files as $file) {
            if ($file['filetype'] == 'css') {
                $data = array('file' => $file['filename'], 'minify' => 0, 'type' => 1);
            } elseif ($file['filetype'] == 'js') {
                $data = array('file' => $file['filename'], 'minify' => 0, 'type' => 2);
            } else {
                $data = array('file' => $file['filename'], 'minify' => 0, 'type' => 0);
            }
            $result[] = $data;
        }

        $model->saveMinifyFile($result);

        echo json_encode(array('status' => 'true'));
        jexit();
    }


    /*
     * change minify ajax
     */
    public function changeMinify()
    {
        $app = JFactory::getApplication();
        $minify = $app->input->get('minify', '', 'string');
        $id = $app->input->get('id', '', 'string');
        $model = $this->getModel();

        if ($minify == 0) {
            $model->updateMinify($id, 1);
        } else {
            $model->updateMinify($id, 0);
        }
        $this->cleanCaches();
        echo json_encode((array('status' => 'true')));
        jexit();
    }

    /*
     * select All
     *
     */
    public function activeAll()
    {
        //Check token
        if (!JSession::checkToken('GET')) {
            echo json_encode(array('status' => 'error'));
            jexit();
        }
        $arr = array();
        $ids = JFactory::getApplication()->input->get('ids', '', 'array');
        $model = $this->getModel();
        $db = JFactory::getDbo();
        if (!empty($ids)) {
            foreach ($ids as $id) {
                if ($id != 'select' && $id != '1' && $id != '') {
                    $arr[] = (int)$id;
                }
            }
            if (!empty($arr)) {
                $state = $model->getStateMinify($arr[0]);
                if ($state) {
                    $model->changeMinify($arr, 0);
                } else {
                    $model->changeMinify($arr, 1);
                }
            }
        }
        $this->cleanCaches();
        echo json_encode(array('status' => 'true'));
        jexit();
    }

    /*
     * Change state of group minify
     *
     */
    public function changeStateGroup()
    {
        //Check token
        if (!JSession::checkToken('GET')) {
            echo json_encode(array('status' => 'error'));
            jexit();
        }

        $state = JFactory::getApplication()->input->get('state', '', 'string');
        $type = JFactory::getApplication()->input->get('type', '', 'string');

        $newState = 0;
        if ($state == '0') {
            $newState = 1;
        }
        speedcacheComponentHelper::setParams(array($type => $newState) );

        echo json_encode(array('status' => 'true'));
        jexit();
    }

    /**
     * Clean admin and front system cache
     */
    protected function cleanCaches()
    {
        //Clean frontend cache
        $conf = JFactory::getConfig();
        $options = array(
            'defaultgroup' => '',
            'cachebase' => $conf->get('cache_path', JPATH_SITE . '/cache')
        );
        $cache = JCache::getInstance('callback', $options);
        $cache->clean();

        //Clean backend cache
        $options = array(
            'defaultgroup' => '',
            'cachebase' => JPATH_ADMINISTRATOR . '/cache'
        );
        $cache = JCache::getInstance('callback', $options);
        $cache->clean();
    }
}

/**
 * Class IgnorantRecursiveDirectoryIterator
 */
class IgnorantRecursiveDirectoryIterator extends RecursiveDirectoryIterator
{
    /**
     * @return IgnorantRecursiveDirectoryIterator|RecursiveArrayIterator
     */
    public function getChildren()
    {
        try {
            return new IgnorantRecursiveDirectoryIterator($this->getPathname());
        } catch (UnexpectedValueException $e) {
            return new RecursiveArrayIterator(array());
        }
    }
}
