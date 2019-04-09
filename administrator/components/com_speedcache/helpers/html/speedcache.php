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
 * JHtml administrator speedcache class.
 *
 * @since  1.6
 */
class JHtmlSpeedcache
{
    /**
     * Get the HTML code of the state switcher
     *
     * @param   int $value The state value
     * @param   int $i Row number
     * @param   boolean $canChange Can the user change the state?
     *
     * @return  string
     *
     * @since   1.6
     *
     * @deprecated  4.0  Use JHtmlspeedcache::status() instead
     */
    public static function state($value = 0, $i = 0, $canChange = false)
    {
        // Log deprecated speedcache
        JLog::add(
            'JHtmlspeedcache::state() is deprecated. Use JHtmlspeedcache::status() instead.',
            JLog::WARNING,
            'deprecated'
        );

        // Note: $i is required but has to be an optional argument in the function call due to argument order
        if (null === $i) {
            throw new InvalidArgumentException('$i is a required argument in JHtmlspeedcache::state');
        }

        // Note: $canChange is required but has to be an optional argument in the function call due to argument order
        if (null === $canChange) {
            throw new InvalidArgumentException('$canChange is a required argument in JHtmlspeedcache::state');
        }

        return static::statusCacheGuest($i, $value, $canChange);
    }

    /**
     * Get the HTML code of the state switcher
     *
     * @param   int $i Row number
     * @param   int $value The state value
     * @param   boolean $canChange Can the user change the state?
     *
     * @return  string
     *
     * @since   3.4
     */
    public static function statusCacheGuest($i, $value = 0, $canChange = false, $type)
    {
        $typeof = '';
        // Array of image, task, title, action.
        $states = array(
            1 => array('publish', 'urls.uncacheGuest', JText::_('COM_SPEEDCACHE_OPTION_CACHE_GUEST'), 'Set uncache guest'),
            0 => array('unpublish', 'urls.cacheGuest', JText::_('COM_SPEEDCACHE_OPTION_UNCACHE_GUEST'), 'Set cache guest'),
        );

        $state = JArrayHelper::getValue($states, (int)$value, $states[0]);
        $icon = $state[0];

        switch ($type) {
            case 1:
                $typeof = 'url_include';
                break;
            case 2:
                $typeof = 'rules_include';
                break;
            case 3:
                $typeof = 'url_exclude';
                break;
            default:
                $typeof = 'rules_exclude';
        }
        $html = '';
        if ($canChange) {
            $html = '<a href="#" onclick="return listItemTask(\'' . $typeof . $i . '\',\'' . $state[1] . '\')" class="btn btn-micro hasTooltip'
                . ($value == 1 ? ' active' : '') . '" title="' . JHtml::tooltipText($state[2]) . '"><span class="icon-' . $icon . '"></span></a>';
        }

        return $html;
    }

    /**
     * Get the HTML code of the state switcher
     *
     * @param   int $i Row number
     * @param   int $value The state value
     * @param   boolean $canChange Can the user change the state?
     *
     * @return  string
     *
     * @since   3.4
     */
    public static function statusPreloadGuest($i, $value = 0, $canChange = false, $type,$itemcache)
    {
        $typeof = '';
        // Array of image, task, title, action.
        $states = array(
            1 => array('publish', 'urls.unpreloadGuest', JText::_('COM_SPEEDCACHE_OPTION_PRELOAD_GUEST'), 'Set preload guest'),
            0 => array('unpublish', 'urls.preloadGuest', JText::_('COM_SPEEDCACHE_OPTION_UNPRELOAD_GUEST'), 'Unset preload guest'),
        );

        $state = JArrayHelper::getValue($states, (int)$value, $states[0]);
        $icon = $state[0];

        switch ($type) {
            case 1:
                $typeof = 'url_include';
                break;
            case 2:
                $typeof = 'rules_include';
                break;
            case 3:
                $typeof = 'url_exclude';
                break;
            default:
                $typeof = 'rules_exclude';
        }
        $html = '';
        //disable click states if not cache public
        if($itemcache == '1'){
            $event = 'listItemTask(\'' . $typeof . $i . '\',\'' . $state[1] . '\')';
            $class = '';
        }else{
            $event = 'false';
            $class = 'disabled';
        }
        if ($canChange) {
            $html = '<a  href="#" onclick="return '.$event.'" class="'.$class.' btn btn-micro hasTooltip'
                . ($value == 1 ? ' active' : '') . '" title="' . JHtml::tooltipText($state[2]) . '"><span class="icon-' . $icon . '"></span></a>';
        }

        return $html;
    }


    public static function statusCacheLogged($i, $value = 0, $canChange = false, $type)
    {
        $typeof = '';
        // Array of image, task, title, action.
        $states = array(
            1 => array('publish', 'urls.uncacheLogged', JText::_('COM_SPEEDCACHE_OPTION_CACHE_LOGGED'), 'Set cache logged'),
            0 => array('unpublish', 'urls.cacheLogged', JText::_('COM_SPEEDCACHE_OPTION_UNCACHE_LOGGED'), 'Unset cache logged'),
        );

        $state = JArrayHelper::getValue($states, (int)$value, $states[0]);
        $icon = $state[0];

        switch ($type) {
            case 1:
                $typeof = 'url_include';
                break;
            case 2:
                $typeof = 'rules_include';
                break;
            case 3:
                $typeof = 'url_exclude';
                break;
            default:
                $typeof = 'rules_exclude';
        }
        $html = '';
        if ($canChange) {
            $html = '<a href="#" onclick="return listItemTask(\'' . $typeof . $i . '\',\'' . $state[1] . '\')" class="btn btn-micro hasTooltip'
                . ($value == 1 ? ' active' : '') . '" title="' . JHtml::tooltipText($state[2]) . '"><span class="icon-' . $icon . '"></span></a>';
        }

        return $html;
    }

    public static function statusPreloadLogged($i, $value = 0, $canChange = false, $type,$itemcache)
    {
        $typeof = '';
        // Array of image, task, title, action.
        $states = array(
            1 => array('publish', 'urls.unpreloadLogged', JText::_('COM_SPEEDCACHE_OPTION_PRELOADLOGGED'), 'Set preload logged'),
            0 => array('unpublish', 'urls.preloadLogged', JText::_('COM_SPEEDCACHE_OPTION_UNPRELOADLOGGED'), 'Unset preload logged'),
        );

        $state = JArrayHelper::getValue($states, (int)$value, $states[0]);
        $icon = $state[0];

        switch ($type) {
            case 1:
                $typeof = 'url_include';
                break;
            case 2:
                $typeof = 'rules_include';
                break;
            case 3:
                $typeof = 'url_exclude';
                break;
            default:
                $typeof = 'rules_exclude';
        }
        $html = '';

        //disable click states if not cache public
        if($itemcache == '1'){
            $event = 'listItemTask(\'' . $typeof . $i . '\',\'' . $state[1] . '\')';
            $class = '';
        }else{
            $event = 'false';
            $class = 'disabled';
        }

        if ($canChange) {
            $html = '<a href="#" onclick="return '.$event.' " class="'.$class.' btn btn-micro hasTooltip'
                . ($value == 1 ? ' active' : '') . '" title="' . JHtml::tooltipText($state[2]) . '"><span class="icon-' . $icon . '"></span></a>';
        }

        return $html;
    }

    public static function statusPreloadPerUser($i, $value = 0, $canChange = false, $type, $itemcache)
    {
        $typeof = '';
        // Array of image, task, title, action.
        $states = array(
            1 => array('publish', 'urls.unpreloadPerUser', JText::_('COM_SPEEDCACHE_OPTION_PRELOAD_PER_USER'), 'Set preload per user'),
            0 => array('unpublish', 'urls.preloadPerUser', JText::_('COM_SPEEDCACHE_OPTION_UNPRELOAD_PER_USER'), 'Unset preload per user'),
        );

        $state = JArrayHelper::getValue($states, (int)$value, $states[0]);
        $icon = $state[0];

        switch ($type) {
            case 1:
                $typeof = 'url_include';
                break;
            case 2:
                $typeof = 'rules_include';
                break;
            case 3:
                $typeof = 'url_exclude';
                break;
            default:
                $typeof = 'rules_exclude';
        }
        $html = '';

        //disable click states if not cache public
        if($itemcache == '1'){
            $event = 'listItemTask(\'' . $typeof . $i . '\',\'' . $state[1] . '\')';
            $class = '';
        }else{
            $event = 'false';
            $class = 'disabled';
        }

        if ($canChange) {
            $html = '<a href="#" onclick="return '.$event.' " class="'.$class.' btn btn-micro hasTooltip'
                . ($value == 1 ? ' active' : '') . '" title="' . JHtml::tooltipText($state[2]) . '"><span class="icon-' . $icon . '"></span></a>';
        }

        return $html;
    }

    public static function statusExcludeCacheGuest($i, $value = 0, $canChange = false, $type)
    {
        $typeof = '';
        // Array of image, task, title, action.
        $states = array(
            1 => array('publish', 'urls.unexcludeGuest', JText::_('COM_SPEEDCACHE_OPTION_EXCLUDE_GUEST'), 'Set excludeGuest'),
            0 => array('unpublish', 'urls.excludeGuest', JText::_('COM_SPEEDCACHE_OPTION_UNEXCLUDE_GUEST'), 'Unset exclude guest'),
        );

        $state = JArrayHelper::getValue($states, (int)$value, $states[0]);
        $icon = $state[0];

        switch ($type) {
            case 1:
                $typeof = 'url_include';
                break;
            case 2:
                $typeof = 'rules_include';
                break;
            case 3:
                $typeof = 'url_exclude';
                break;
            default:
                $typeof = 'rules_exclude';
        }
        $html = '';
        if ($canChange) {
            $html = '<a href="#" onclick="return listItemTask(\'' . $typeof . $i . '\',\'' . $state[1] . '\')" class="btn btn-micro hasTooltip'
                . ($value == 1 ? ' active' : '') . '" title="' . JHtml::tooltipText($state[2]) . '"><span class="icon-' . $icon . '"></span></a>';
        }

        return $html;
    }

    public static function statusExcludeCacheLogged($i, $value = 0, $canChange = false, $type)
    {
        $typeof = '';
        // Array of image, task, title, action.
        $states = array(
            1 => array('publish', 'urls.unexcludeLogged', JText::_('COM_SPEEDCACHE_OPTION_EXCLUDE_LOGGED'), 'Set exclude logged'),
            0 => array('unpublish', 'urls.excludeLogged', JText::_('COM_SPEEDCACHE_OPTION_UNEXCLUDE_LOGGED'), 'Unset exclude logged'),
        );

        $state = JArrayHelper::getValue($states, (int)$value, $states[0]);
        $icon = $state[0];

        switch ($type) {
            case 1:
                $typeof = 'url_include';
                break;
            case 2:
                $typeof = 'rules_include';
                break;
            case 3:
                $typeof = 'url_exclude';
                break;
            default:
                $typeof = 'rules_exclude';
        }
        $html = '';
        if ($canChange) {
            $html = '<a href="#" onclick="return listItemTask(\'' . $typeof . $i . '\',\'' . $state[1] . '\')" class="btn btn-micro hasTooltip'
                . ($value == 1 ? ' active' : '') . '" title="' . JHtml::tooltipText($state[2]) . '"><span class="icon-' . $icon . '"></span></a>';
        }

        return $html;
    }

    public static function statusIgnoreParams($i, $value = 0, $canChange = false, $type)
    {
        $typeof = '';
        // Array of image, task, title, action.
        $states = array(
            1 => array('publish', 'urls.unignoreParams', JText::_('COM_SPEEDCACHE_OPTION_IGNORE_PARAMS'), 'Set ignore parameter'),
            0 => array('unpublish', 'urls.ignoreParams', JText::_('COM_SPEEDCACHE_OPTION_UNIGNORE_PARAMS'), 'Unset ignore parameter'),
        );

        $state = JArrayHelper::getValue($states, (int)$value, $states[0]);
        $icon = $state[0];

        switch ($type) {
            case 1:
                $typeof = 'url_include';
                break;
            case 2:
                $typeof = 'rules_include';
                break;
            case 3:
                $typeof = 'url_exclude';
                break;
            default:
                $typeof = 'rules_exclude';
        }
        $html = '';
        if ($canChange) {
            $html = '<a href="#" onclick="return listItemTask(\'' . $typeof . $i . '\',\'' . $state[1] . '\')" class="btn btn-micro hasTooltip'
                . ($value == 1 ? ' active' : '') . '" title="' . JHtml::tooltipText($state[2]) . '"><span class="icon-' . $icon . '"></span></a>';
        }

        return $html;
    }

}
