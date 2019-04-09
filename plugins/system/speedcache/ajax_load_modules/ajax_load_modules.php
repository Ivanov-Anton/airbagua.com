<?php

/*
 * Using lazy-loading-xt jquery plugin
 */

class SCAjaxLoadModules {

    public function __construct()
    {

    }

    /**
     * Change module to load
     * @return mixed
     */
    public function setModules($content)
    {
        $contentModules = $this->getContentModules();

        foreach ($contentModules as $cm) {
            list($module, $interval ,$time ,$contentMod) = $cm;

            $html = '<div rel="'.$module.'" alt="'.$time.'"  class="sc-display-module '.$interval.'">';
            $html .= '<img src="plugins/system/speedcache/ajax_load_modules/image/modajaxloader.gif" ></div>';

            // Replace module to loader gif
            $content = str_replace($contentMod, $html, $content);
        }

        return $content;
    }

    /**
     * Get content per modules
     * @return mixed
     */
    public function getContentModules()
    {
        $result = $itemArr = array();
        $items = JModuleHelper::getModuleList();
        if (!empty($items)) {
            foreach ($items as $item) {

                $params = json_decode($item->params);

                if (isset($params->sc_ajax_load_module) && $params->sc_ajax_load_module == 1) {
                    $interval = '';
                    $time = 0;
                    if(isset($params->sc_ajax_load_module_interval) && $params->sc_ajax_load_module_interval== 1) {
                        $interval = 'recurrent';
                    }
                    if(isset($params->sc_ajax_loadmod_interval_time) && $params->sc_ajax_loadmod_interval_time > 0) {
                        $time = $params->sc_ajax_loadmod_interval_time;
                    }
                    $module = JModuleHelper::getModule($item->module);
                    $contents = JModuleHelper::renderModule($module);
                    // [module,interval,time,content]
                    $itemArr[] = $item->module;
                    $itemArr[] = $interval;
                    $itemArr[] = $time;
                    $itemArr[] = $contents;
                    // Get success content of module
                    if (!empty($contents)) {
                        $result[] = $itemArr;
                    }
                    unset($itemArr);
                }
            }
        }

        return $result;
    }
}
