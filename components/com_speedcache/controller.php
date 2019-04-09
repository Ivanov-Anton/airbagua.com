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
 * speedcache master display controller.
 *
 * @since  1.6
 */
class speedcacheController extends JControllerLegacy
{
    /*
     * Preload all the page which are able to been cached
     */
    public function preload()
    {
        //Check token
        $params = JComponentHelper::getParams('com_speedcache');
        $preloadingToken = $params->get('preloading_token');
        if (JFactory::getApplication()->input->get('token') !== $preloadingToken) {
            jexit('Wrong token');
        }

        ignore_user_abort(true);

        while (ob_get_level() != 0) {
            ob_end_clean();
        }

        $echo = 'Preloading process will continue in background...';

        header("Connection: close", true);
        header("Content-Encoding: none\r\n");
        header("Content-Length: " . strlen($echo), true);

        echo $echo;

        flush();
        @ob_flush();

        //Make a request for each url in database to load the cache (guest)
        $dbo = JFactory::getDbo();
        $query = "SELECT * FROM #__speedcache_urls WHERE cacheguest=1 AND preloadguest=1 AND type = 'include'";
        $dbo->setQuery($query);
        $urls = $dbo->loadObjectList();
        foreach ($urls as $url) {
            if (strpos($url->url, JUri::root()) === false) {
                $preloadUrl = JUri::root() . $url->url;
                $this->curlPreload($preloadUrl, $preloadingToken);
            }
        }
        //Make cache preload when have user
        $query = "SELECT * FROM #__users WHERE block != 1 AND activation != 1";
        $dbo->setQuery($query);
        $users = $dbo->loadObjectList();

        //on preload per user
        $query = "SELECT * FROM #__speedcache_urls WHERE cachelogged=1 AND preloadlogged = 1 AND type = 'include'";
        $dbo->setQuery($query);
        $urls = $dbo->loadObjectList();
        if (!empty($urls)) {
            foreach ($urls as $url) {
                if (strpos($url->url, JUri::root()) === false) {
                    $preloadUrl = JUri::root() . $url->url;
                    if ($url->preloadperuser == '1') {
                        //active per user
                        foreach ($users as $user) {
                            $this->curlPreload($preloadUrl, $preloadingToken, $user, 'user');
                        }
                    } else {
                        //preload cache with first user in database logined
                        $this->curlPreload($preloadUrl, $preloadingToken, $users[0], 'user');
                    }
                }
            }
        }

        jexit();
    }

    /**
     * Read content per url from user or guest
     * @param $url
     * @param $preloadingToken
     * @param bool $user
     * @param bool $type
     */
    public function curlPreload($url, $preloadingToken, $user = false, $type = false)
    {
        // Fix Authorization not return in http (so change Authorization (old version) to Authorization-sc)
        $header = array();
        if ($type == 'user') {
            $header = array(
                'Authorization-sc:Bearer_' . $preloadingToken,
                'Userid:' . $user->id
            );
        } else {
            $header = array('Authorization-sc:Bearer_' . $preloadingToken);
        }

        if (function_exists('curl_exec')) {
            sleep(1);
            set_time_limit(30);
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
            curl_setopt($ch, CURLOPT_COOKIE, 'XDEBUG_SESSION=16408');
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
            curl_setopt($ch, CURLOPT_TIMEOUT, 25);
            curl_setopt($ch, CURLOPT_NOBODY, 0);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_exec($ch);
            curl_close($ch);
        }
    }
}
