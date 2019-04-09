<?php
/*
 *  Based on some work of autoptimize plugin 
 */
defined('_JEXEC') or die;

class SCMinificationScripts extends SCMinificationBase
{
    private $scripts = array();
    private $dontmove = array('document.write', 'html5.js', 'show_ads.js', 'google_ad', 'blogcatalog.com/w',
        'tweetmeme.com/i', 'mybloglog.com/', 'histats.com/js', 'ads.smowtion.com/ad.js',
        'statcounter.com/counter/counter.js', 'widgets.amung.us', 'ws.amazon.com/widgets',
        'media.fastclick.net', '/ads/', 'comment-form-quicktags/quicktags.php', 'edToolbar', 'intensedebate.com',
        'scripts.chitika.net/', '_gaq.push', 'jotform.com/', 'admin-bar.min.js', 'GoogleAnalyticsObject',
        'plupload.full.min.js', 'syntaxhighlighter', 'adsbygoogle', 'gist.github.com', '_stq', 'nonce',
        'post_id', 'data-noptimize');
    private $domove = array('gaJsHost', 'load_cmc', 'jd.gallery.transitions.js',
        'swfobject.embedSWF(', 'tiny_mce.js', 'tinyMCEPreInit.go');
    private $domovelast = array('addthis.com', '/afsonline/show_afs_search.js', 'disqus.js',
        'networkedblogs.com/getnetworkwidget', 'infolinks.com/js/', 'jd.gallery.js.php', 'jd.gallery.transitions.js',
        'swfobject.embedSWF(', 'linkwithin.com/widget.js', 'tiny_mce.js', 'tinyMCEPreInit.go');
    private $trycatch = false;
    private $forcehead = true;
    private $defer = false;
    private $include_inline = false;
    private $jscode = array();
    private $jscode_afer_group = '';
    private $minified_code = array();
    private $url = '';
    private $restofcontent = '';
    private $md5hash = '';
    private $whitelist = '';
    private $inject_min_late = '';
    protected $turn_on_js = array();
    private $group_js = false;
    private $js_group_val = array();
    private $js_min_arr = array();
    private $url_group_arr = array();
    private $js_not_minify = array();
    private $check_minify_exist = false;
    private $jsExcludes = array();
    private $script_to_defer = array();
    protected $matches = array();
    private $cache_external = false;
    private $external_scripts = array();
    private $external_local_path = array();
    /**
     * Reads the page and collects script tags
     * @param $options
     * @return bool
     */
    public function read($options)
    {
        // only header?
        if ($options['justhead'] == true) {
            $content = explode('</head>', $this->content, 2);
            $this->content = $content[0] . '</head>';
            $this->restofcontent = $content[1];
        }

        // include inline?
        if ($options['include_inline'] == true) {
            $this->include_inline = true;
        }

        // group js?
        if ($options['groupjs'] == true) {
            $this->group_js = true;
        }
        // Exclude js from group speedcache 2.1
        if (!empty($options['jsExcludes'])) {
            $this->jsExcludes = array_map('trim', $options['jsExcludes']);
        }
        if ($options['defer']) {
            $this->defer = true;
        }
        // filter to "late inject minified JS", default to true for now (it is faster)
        $this->inject_min_late = true;

        //cache external js
        if (!empty($options['cache_external'])) {
            $this->cache_external = $options['cache_external'];
        }
        // get extra exclusions settings or filter
        $excludeJS = $options['js_exclude'];
        if ($excludeJS !== "") {
            $exclJSArr = array_filter(array_map('trim', explode(",", $excludeJS)));
            $this->dontmove = array_merge($exclJSArr, $this->dontmove);
        }

        //Should we add try-catch?
        if ($options['trycatch'] == true) {
            $this->trycatch = true;
        }

        //get dk
        if (!empty($options['turn_on_js'])) {
            $this->turn_on_js = $options['turn_on_js'];
        }

        // force js in head?
        if ($options['forcehead'] == true) {
            $this->forcehead = true;
        } else {
            $this->forcehead = false;
        }

        // noptimize me
        $this->content = $this->hideNoptimize($this->content);

        // Save IE hacks
        $this->content = $this->hideIehacks($this->content);

        // comments
        $this->content = $this->hideComments($this->content);

        //Get script files
        if (preg_match_all('#<script.*</script>#Usmi', $this->content, $matches)) {
            $this->matches = $matches[0];
            foreach ($matches[0] as $tag) {
                // only consider aggregation whitelisted in should_aggregate-function
                if (!$this->shouldAggregate($tag)) {
                    continue;
                }
                if (preg_match('#src=("|\')(.*)("|\')#Usmi', $tag, $source)) {
                    // External script
                    $url = current(explode('?', $source[2], 2));
                    // Exclude file if js exclude exist
                    if ($this->checkExcludeFile($url, $this->jsExcludes)) {
                        continue;
                    }
                    $path = $this->getpath($url);
                    if ($path !== false && preg_match('#\.js$#', $path)) {
                        if ($this->ismergeable($tag)) {
                            //We can merge it
                            $this->scripts[$url] = $path;
                            if ($this->group_js) {
                                $this->content = str_replace($tag, '', $this->content);
                            }
                        }
                    } else {
                        //External script (example: google analytics)
                        preg_match('/(src=["\'](.*?)["\'])/', $tag, $match);
                        $split = preg_split('/["\']/', $match[0]); // split by quotes
                        if (!empty($split[1])) {
                            $this->external_scripts[$tag] = $split[1];
                        }
                    }
                }else {
                    // Inline script
                    if ($this->ismovable($tag)) {
                        // unhide comments, as javascript may be wrapped in comment-tags for old times' sake
                        $tag = $this->restoreComments($tag);
                        // Set url to compare for move to footer
                        if (preg_match('#<script.*>(.*)</script>#Usmi', $tag, $code)) {
                            $code = preg_replace('#.*<!\[CDATA\[(?:\s*\*/)?(.*)(?://|/\*)\s*?\]\]>.*#sm', '$1', $code[1]);
                            $code = preg_replace('/(?:^\\s*<!--\\s*|\\s*(?:\\/\\/)?\\s*-->\\s*$)/', '', $code);

                            if ($this->group_js) {
                                $this->scripts[$tag] = 'SC_INLINE;' . $code;
                                $this->content = str_replace($tag, '', $this->content);
                            }
                        }
                    }
                }
            }
            return true;
        }
        // No script files, great ;-)
        return false;
    }

    /**
     * Joins and optimizes JS
     * @return bool
     */
    public function minify()
    {
        foreach ($this->scripts as $k => $script) {
            if (preg_match('#^SC_INLINE;#', $script)) {
                //Inline script
                $script = preg_replace('#^SC_INLINE;#', '', $script);
                // re-hide comments to be able to do the removal based on tag from $this->content
                $script = $this->hideComments($script);
                $script = rtrim($script, ";\n\t\r") . ';';

                if ($this->group_js) {
                    $this->jscode[$k] = $script;
                }
            } else {
                //External script
                if ($script !== false && file_exists($script) && is_readable($script)) {
                    $scriptsrc = file_get_contents($script);
                    $scriptsrc = preg_replace('/\x{EF}\x{BB}\x{BF}/', '', $scriptsrc);
                    $scriptsrc = rtrim($scriptsrc, ";\n\t\r") . ';';
                    //Add try-catch?
                    if ($this->trycatch) {
                        $scriptsrc = 'try{' . $scriptsrc . '}catch(e){}';
                    }

                    if (!empty($this->turn_on_js)) {
                        foreach ($this->turn_on_js as $turn) {
                            if (strpos($script, $turn) !== false) {
                                if ($this->group_js) {
                                    $this->jscode[$k] = $scriptsrc;
                                } else {
                                    $this->js_group_val[$k] = $scriptsrc;
                                }
                                // breack to get js not minify to group
                                $this->check_minify_exist = true;
                            }
                        }
                    }
                    // Break for get css not minify
                    if ($this->check_minify_exist) {
                        $this->check_minify_exist = false;
                    } else {
                        if ($this->defer) {
                            $this->script_to_defer[$k] = $this->getUrl($script);
                        }
                        $this->jscode[$k.'_sc_not_minify'] = $scriptsrc;
                    }
                }
            }
        }

        if ($this->group_js) {
            $hashname = '';
            foreach ($this->jscode as $jscode) {
                $hashname .= $jscode;
            }
            $this->md5hash = md5($hashname);
            $ccheck = new SCMinificationCache($this->md5hash, 'js');
            if ($ccheck->check()) {
                $this->jscode_afer_group = $ccheck->retrieve();
                return true;
            }
            unset($ccheck);

            $minifyCode = '';
            //Check for already-minified code
            foreach ($this->jscode as $k => $code) {
                // Remove Strict Mode to fix error js when group
                if (preg_match('#^"use strict";#', $code)) {
                    $code = preg_replace('#^"use strict";#', '', $code);
                }
                if (strpos($k ,'_sc_not_minify') === false) {
                    //$this->jscode has all the uncompressed code now.
                    if (class_exists('JSMin')) {
                        if (@is_callable(array("JSMin", "minify"))) {
                            $tmp_jscode = trim(JSMin::minify($code));
                            if (!empty($tmp_jscode)) {
                                $code = $this->injectMinified($tmp_jscode);
                                unset($tmp_jscode);
                            }
                        }
                    }
                }
                $minifyCode .= "/*SC_GROUP_JS*/" . $code . "\n";
            }

            $this->jscode_afer_group = $minifyCode;
        } else {
            if (!empty($this->js_group_val)) {
                foreach ($this->js_group_val as $k => $jscode) {
                    //Check for already-minified code
                    $this->md5hash = md5($jscode);
                    $ccheck = new SCMinificationCache($this->md5hash, 'js');
                    if ($ccheck->check()) {
                        $js_exist = $ccheck->retrieve();
                        $this->js_min_arr[$k] = $this->md5hash . '_scjsgroup_' . $js_exist;
                        continue;
                    }
                    unset($ccheck);

                    //$this->jscode has all the uncompressed code now.
                    if (class_exists('JSMin')) {
                        if (@is_callable(array("JSMin", "minify"))) {
                            $tmp_jscode = trim(JSMin::minify($jscode));
                            if (!empty($tmp_jscode)) {
                                $jscode = $tmp_jscode;
                                unset($tmp_jscode);
                            }
                        }
                    }

                    $jscode = $this->injectMinified($jscode);
                    $this->js_min_arr[$k] = $this->md5hash . '_scjsgroup_' . $jscode;
                }
            }
        }
        return true;
    }

    //Caches the JS in uncompressed, deflated and gzipped form.
    public function cache()
    {
        if ($this->group_js) {
            if (!empty($this->jscode_afer_group)) {
                $cache = new SCMinificationCache($this->md5hash, 'js');
                if (!$cache->check()) {
                    //Cache our code
                    $cache->cache($this->jscode_afer_group, 'text/javascript');
                }
                $this->url = SC_JOOMLA_SITE_URL . $cache->getname();
            }
        } else {
            if (!empty($this->js_min_arr)) {
                foreach ($this->js_min_arr as $k => $js_min) {
                    $namehash = substr($js_min, 0, strpos($js_min, '_scjsgroup_'));
                    $js_code = substr($js_min, strpos($js_min, '_scjsgroup_') + strlen('_scjsgroup_'));
                    $cache = new SCMinificationCache($namehash, 'js');
                    if (!$cache->check()) {
                        //Cache our code
                        $cache->cache($js_code, 'text/javascript');
                    }
                    $this->url_group_arr[$k] = SC_JOOMLA_SITE_URL . $cache->getname();
                }
            }
        }

        // Cache external script
        if ($this->cache_external) {
            if (!empty($this->external_scripts)) {
                foreach ($this->external_scripts as $k => $v) {

                    if (strpos($v, '//') === 0) {
                        if (self::isSsl()) {
                            $http = "https:";
                        } else {
                            $http = "http:";
                        }
                        $v = $http . $v;
                    }

                    $script = $this->getExternalData($v);
                    if (empty($script)) {
                        continue;
                    }
                    $hashName = md5($script);
                    $ccache = new SCMinificationCache($hashName, 'js');
                    if (!$ccache->check()) {
                        //Cache our code
                        $ccache->cache($script, 'text/javascript');
                    }

                    $this->external_local_path[$k] = SC_JOOMLA_SITE_URL . $ccache->getname();
                }
            }
        }
    }
    /**
     * Returns the content
     * @return mixed|string
     */
    public function getcontent()
    {
        // Restore the full content
        if (!empty($this->restofcontent)) {
            $this->content .= $this->restofcontent;
            $this->restofcontent = '';
        }
        // Call JS files at the end of the page load to eliminate render blocking elements
        $defer = "";
        $replaceTag = array("</head>", "before");
        if ($this->defer) {
            $defer = "defer ";
        }

        if ($this->group_js) {
            $bodyreplacementpayload = '<script type="text/javascript" ' . $defer . 'src="' . $this->url . '"></script>';
            $this->injectInHtml($bodyreplacementpayload, $replaceTag);
        } else {
            if (!empty($this->url_group_arr)) {
                foreach ($this->url_group_arr as $k => $url) {
                    $script = '<script type="text/javascript" ' . $defer . 'src="' . $url . '"></script>';
                    $this->injectMinifyToHtml($k, $script);
                }
            }

            //Add defer to another script
            if (!empty($this->script_to_defer)) {
                foreach ($this->script_to_defer as $k => $url) {
                    $deferscript = '<script type="text/javascript" ' . $defer . 'src="' . $url . '"></script>';
                    $this->injectMinifyToHtml($k, $deferscript);
                }
            }
        }

        //Inject External script
        if (!empty($this->external_local_path)) {
            foreach ($this->external_local_path as $k => $url) {
                $script = '<script type="text/javascript" ' . $defer . 'src="' . $url . '"></script>';
                $this->injectMinifyToHtml($k, $script);
            }
        }
        // restore comments
        $this->content = $this->restoreComments($this->content);

        // Restore IE hacks
        $this->content = $this->restoreIehacks($this->content);

        // Restore noptimize
        $this->content = $this->restoreNoptimize($this->content);
        // Return the modified HTML
        return $this->content;
    }

    /**
     * Checks against the white- and blacklists
     * @param $tag
     * @return bool
     */
    private function ismergeable($tag)
    {
        if (!empty($this->whitelist)) {
            foreach ($this->whitelist as $match) {
                if (strpos($tag, $match) !== false) {
                    return true;
                }
            }
            // no match with whitelist
            return false;
        } else {
            foreach ($this->domove as $match) {
                if (strpos($tag, $match) !== false) {
                    //Matched something
                    return false;
                }
            }

            if ($this->movetolast($tag)) {
                return false;
            }

            foreach ($this->dontmove as $match) {
                if (strpos($tag, $match) !== false) {
                    //Matched something
                    return false;
                }
            }

            // If we're here it's safe to merge
            return true;
        }
    }
    /**
     * Checks agains the blacklist
     * @param $tag
     * @return bool
     */
    private function ismovable($tag)
    {
        if ($this->include_inline !== true) {
            return false;
        }

        foreach ($this->domove as $match) {
            if (strpos($tag, $match) !== false) {
                //Matched something
                return true;
            }
        }

        if ($this->movetolast($tag)) {
            return true;
        }

        foreach ($this->dontmove as $match) {
            if (strpos($tag, $match) !== false) {
                //Matched something
                return false;
            }
        }

        //If we're here it's safe to move
        return true;
    }

    /**
     * @param $tag
     * @return bool
     */
    private function movetolast($tag)
    {
        foreach ($this->domovelast as $match) {
            if (strpos($tag, $match) !== false) {
                //Matched, return true
                return true;
            }
        }

        //Should be in 'first'
        return false;
    }

    /**
     * Determines wheter a <script> $tag should be aggregated or not.
     *
     * We consider these as "aggregation-safe" currently:
     * - script tags without a `type` attribute
     * - script tags with an explicit `type` of `text/javascript`, 'text/ecmascript',
     *   'application/javascript' or 'application/ecmascript'
     *
     * Everything else should return false.
     *
     * @param string $tag
     * @return bool
     *
     * original function by https://github.com/zytzagoo/ on his AO fork, thanks Tomas!
     */
    public function shouldAggregate($tag)
    {
        preg_match('#<(script[^>]*)>#i', $tag, $scripttag);
        if (strpos($scripttag[1], 'type=') === false) {
            return true;
        } elseif (preg_match(
            '/type=["\']?(?:text|application)\/(?:javascript|ecmascript)["\']?/i',
            $scripttag[1]
        )) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Get content of external script
     * @param $url
     * @return mixed
     */
    public function getExternalData($url)
    {
        $data = '';
        if (function_exists('curl_exec')) {
            $ch = curl_init();
            $timeout = 5;
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            $data = curl_exec($ch);
            curl_close($ch);
        }
        return $data;
    }
}
