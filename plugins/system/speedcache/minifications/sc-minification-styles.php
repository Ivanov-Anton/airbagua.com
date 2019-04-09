<?php
/* 
 *  Based on some work of autoptimize plugin 
 */
defined('_JEXEC') or die;

class SCMinificationStyles extends SCMinificationBase
{
    private $dontmove = '';
    private $css = array();
    private $csscode = array();
    private $url = '';
    private $restofcontent = '';
    private $hashmap = '';
    private $alreadyminified = false;
    private $defer = false;
    private $cssinlinesize = '';
    private $grfonts = array("fonts.googleapis.com");
    private $group_fonts = false;
    private $include_inline = false;
    private $inject_min_late = '';
    private $turn_on_css = array();
    private $group_css = false;
    private $css_group_val = array();
    private $css_min_arr = array();
    private $url_group_arr = array();
    private $check_minify_exist = false;
    private $css_not_minify = '';
    private $csscode1 = '';
    private $hash = '';
    private $cssExcludes = array();
    private $css_notminify_to_defer = array();
    protected $cssmatches = array();
    /**
     * Reads the page and collects style tags
     * @param $options
     * @return bool
     */
    public function read($options)
    {
        $this->cssinlinesize = 256;

        // filter to "late inject minified CSS", default to true for now (it is faster)
        $this->inject_min_late = true;

        // Remove everything that's not the header
        if ($options['justhead'] == true) {
            $content = explode('</head>', $this->content, 2);
            $this->content = $content[0] . '</head>';
            $this->restofcontent = $content[1];
        }

        // group css?
        if ($options['groupcss'] == true) {
            $this->group_css = true;
        }
        // group font
        if ($options['groupfonts'] == true) {
            $this->group_fonts = true;
        }

        // Exclude js from group speedcache 2.1
        if (!empty($options['cssExcludes'])) {
            $this->cssExcludes = array_map('trim', $options['cssExcludes']);
        }

        // what CSS shouldn't be autoptimized
        $excludeCSS = $options['css_exclude'];

        if ($excludeCSS !== "") {
            $this->dontmove = array_filter(array_map('trim', explode(",", $excludeCSS)));
        } else {
            $this->dontmove = "";
        }

        // should we defer css?
        // value: true/ false
        $this->defer = $options['defer'];

        //get dk
        if (!empty($options['turn_on_css'])) {
            $this->turn_on_css = $options['turn_on_css'];
        }
        // noptimize me
        $this->content = $this->hideNoptimize($this->content);

        // exclude (no)script, as those may contain CSS which should be left as is
        if (strpos($this->content, '<script') !== false) {
            $this->content = preg_replace_callback(
                '#<(?:no)?script.*?<\/(?:no)?script>#is',
                function ($matches) {
                    return "%%SCRIPT%%".base64_encode($matches[0])."%%SCRIPT%%";
                },
                $this->content
            );
        }

        // Save IE hacks
        $this->content = $this->hideIehacks($this->content);

        // hide comments
        $this->content = $this->hideComments($this->content);

        // Get <style> and <link>
        if (preg_match_all('#(<style[^>]*>.*</style>)|(<link[^>]*stylesheet[^>]*>)#Usmi', $this->content, $matches)) {
            $this->cssmatches = $matches[0];
            foreach ($matches[0] as $tag) {
                if ($this->group_fonts && $this->isremovable($tag, $this->grfonts)) {
                    $media = 'all';
                    if (preg_match('#<link.*href=("|\')(.*)("|\')#Usmi', $tag, $source)) {
                        // google font link
                        $this->css[$tag] = array($media, $source[2]);
                        if ($this->group_css || $this->defer) {
                            $this->content = str_replace($tag, '', $this->content);
                        }
                    }
                } elseif ($this->ismovable($tag)) {
                    // Get the media
                    if (strpos($tag, 'media=') !== false) {
                        preg_match('#media=(?:"|\')([^>]*)(?:"|\')#Ui', $tag, $medias);
                        $media = trim($medias[1]);
                    } else {
                        // No media specified - applies to all
                        $media = 'all';
                    }
                    if (preg_match('#<link.*href=("|\')(.*)("|\')#Usmi', $tag, $source)) {
                        // <link>
                        $url = current(explode('?', $source[2], 2));
                        // Exclude file if css exclude exist
                        if ($this->checkExcludeFile($url, $this->cssExcludes)) {
                            continue;
                        }

                        $path = $this->getpath($url);

                        if ($path !== false && preg_match('#\.css$#', $path)) {
                            // Good link
                            $this->css[$url] = array($media, $path);
                            // Remove the original style tag
                            if ($this->group_css || $this->defer) {
                                $this->content = str_replace($tag, '', $this->content);
                            }
                        }
                    } else {
                        // Group inline
                        if ( preg_match('#<style.*>(.*)</style>#Usmi', $tag, $code)) {
                            $regex = '#^.*<!\[CDATA\[(?:\s*\*/)?(.*)(?://|/\*)\s*?\]\]>.*$#sm';
                            $code = preg_replace($regex, '$1', $code[1]);

                            $this->css[$tag] = array($media, 'SC_INLINE;' . $code);

                            if ($this->group_css || $this->defer) {
                                $this->content = str_replace($tag, '', $this->content);
                            }
                        }
                    }
                    // Do not thing with inline css
                }
            }
            return true;
        }
        // Really, no styles?
        return false;
    }
    /**
     * Joins and optimizes CSS
     * @return bool
     */
    public function minify()
    {
        foreach ($this->css as $k => $group) {
            list($media, $css) = $group;

            if (preg_match('#^SC_INLINE;#', $css)) {
                $css = preg_replace('#^SC_INLINE;#', '', $css);
                $this->csscode[$k] = $css;
            } else {
                //<link>
                if ($css !== false && file_exists($css) && is_readable($css)) {
                    $cssPath = $css;
                    $css = $this->fixurls($cssPath, file_get_contents($cssPath));
                    $css = preg_replace('/\x{EF}\x{BB}\x{BF}/', '', $css);
                    foreach ($this->turn_on_css as $turn) {
                        if (strpos($turn, '?') !== false) {
                            $turn = substr($turn, 0, strpos($turn, '?'));
                        }
                        if (strpos($cssPath, $turn) !== false) {
                            $this->csscode[$k] = $css;
                            $this->css_group_val[$k] = $media . "_sccssgroup_" . $css;
                            // Break for get css not minify
                            $this->check_minify_exist = true;
                        }
                    }
                    // Break for get css not minify
                    if ($this->check_minify_exist) {
                        $this->check_minify_exist = false;
                    } else {
                        // CSS not minify ready
                        $this->csscode[$k.'_sc_not_minify'] = $css;
                        if ($this->defer) {
                            $this->css_notminify_to_defer[$k] = $media . "_sccssdefer_" . $this->getUrl($cssPath);
                        }
                    }
                } else {
                    if (strpos($css, '//') === 0) {
                        if (self::isSsl()) {
                            $http = "https:";
                        } else {
                            $http = "http:";
                        }
                        $css = $http . $css;
                    }

                    if ($this->checkUrlExist($css)) {
                        $cssPath = $css;
                        $css = file_get_contents($css);
                        if ($this->isremovable($cssPath, $this->grfonts)) {
                            //get font css from server
                            $this->csscode[$k] = $css;
                            $this->css_group_val[$k] = $media . "_sccssgroup_" . $css;
                        } else {
                            foreach ($this->turn_on_css as $turn) {
                                if (strpos($turn, '?') !== false) {
                                    $turn = substr($turn, 0, strpos($turn, '?'));
                                }
                                if (strpos($cssPath, $turn) !== false) {
                                    //font
                                    $this->csscode[$k] = $css;
                                    $this->css_group_val[$k] = $media . "_sccssgroup_" . $css;
                                    // Break for get css not minify
                                    $this->check_minify_exist = true;
                                }
                            }
                            // Break for get css not minify
                            if ($this->check_minify_exist) {
                                $this->check_minify_exist = false;
                            } else {
                                // CSS not minify ready
                                $this->csscode[$k.'_sc_not_minify'] .= $css;
                            }
                        }
                    }
                }
            }
        }

        if ($this->group_css == true) {
            // Ready minified css
            $md5hash = '';
            foreach ($this->csscode as $csscode) {
                $md5hash .= $csscode;
            }
            // Check for already-minified code
            $this->hash = md5($md5hash);
            $ccheck = new SCMinificationCache($this->hash, 'css');
            if ($ccheck->check()) {
                $this->hashmap = $ccheck->retrieve();
                return true;
            }
            unset($ccheck);

            // Minify
            $groupCode = '';

            foreach ($this->csscode as $k => $csscode) {

                if (strpos($k ,'_sc_not_minify') === false) {
                    error_log($k);
                    if (class_exists('Minify_CSS_Compressor')) {
                        $tmp_code = trim(Minify_CSS_Compressor::process($csscode));
                    } elseif (class_exists('CSSmin')) {
                        $cssmin = new CSSmin();
                        if (method_exists($cssmin, "run")) {
                            $tmp_code = trim($cssmin->run($csscode));
                        } elseif (@is_callable(array($cssmin, "minify"))) {
                            $tmp_code = trim(CssMin::minify($csscode));
                        }
                    }
                    if (!empty($tmp_code)) {
                        $csscode = $this->injectMinified($tmp_code);
                        unset($tmp_code);
                    }
                }

                $groupCode .= "/*SC_GROUP_CSS*/". $csscode . "\n";
            }

            $fiximports = false;
            $external_imports = '';
            if (preg_match_all('#@import.*(?:;|$)#Usmi', $groupCode, $matches)) {
                foreach ($matches[0] as $import) {
                    if ($this->isremovable($import, $this->grfonts)) {
                        $groupCode = str_replace($import, '', $groupCode);
                        if ($this->group_fonts) {
                            //Read content of google font and add to top
                            if ( preg_match('#^.*((?:https?:|ftp:)?\/\/.*)(?:\').*$#Usmi', $import, $code)) {
                                if (!empty($code[1])) {
                                    if (strpos($code[1], '//') === 0) {
                                        if (self::isSsl()) {
                                            $http = "https:";
                                        } else {
                                            $http = "http:";
                                        }
                                        $code[1] = $http . $code[1];
                                    }
                                    // fix file_get_contents(): SSL operation failed with code
                                    $arrContextOptions=array(
                                        "ssl"=>array(
                                            "verify_peer"=>false,
                                            "verify_peer_name"=>false,
                                        ),
                                    );
                                    $tmp_thiscss = file_get_contents(
                                        $code[1],
                                        false,
                                        stream_context_create($arrContextOptions)
                                    );

                                    if (!empty($tmp_thiscss)) {
                                        $external_imports .= $tmp_thiscss;
                                        unset($tmp_thiscss);
                                    }
                                    unset($code[1]);
                                }
                            }
                        } else {
                            // external imports and general fall-back
                            $external_imports .= $import;
                        }
                        $fiximports = true;
                    } else {
                        if (preg_match('#^.*((?:https?:|ftp:)?\/\/.*)(?:\').*$#Usmi', $import, $source)) {
                            $path = $this->getpath($source[1]);

                            if (file_exists($path) && is_readable($path)) {
                                $code = addcslashes($this->fixurls($path, file_get_contents($path)), "\\");
                                $code = preg_replace('/\x{EF}\x{BB}\x{BF}/', '', $code);

                                if (!empty($code)) {
                                    $regex = '#(/\*FILESTART\*/.*)' . preg_quote($import, '#') . '#Us';
                                    $tmp_thiscss = preg_replace($regex, '/*FILESTART2*/' . $code . '$1', $groupCode);
                                    if (!empty($tmp_thiscss)) {
                                        $groupCode = $tmp_thiscss;
                                        unset($tmp_thiscss);
                                    }
                                    unset($code);
                                }
                            }
                        }
                    }
                }

                // add external imports to top of aggregated CSS
                if ($fiximports) {
                    $groupCode = $external_imports ."\n". $groupCode;
                }
            }

            $this->hashmap = $groupCode;
        } else {
            foreach ($this->css_group_val as $k => $value) {
                $media = substr($value, 0, strpos($value, '_sccssgroup_'));
                $css = substr($value, strpos($value, '_sccssgroup_') + strlen('_sccssgroup_'));
                $hash = md5($css);
                $ccheck = new SCMinificationCache($hash, 'css');
                if ($ccheck->check()) {
                    $css_exist = $ccheck->retrieve();
                    $this->css_min_arr[$k] = $media . "_scmedia_" . $hash . "_sckey_" . $css_exist;
                    continue;
                }
                unset($ccheck);

                // Minify
                if (class_exists('Minify_CSS_Compressor')) {
                    $tmp_code = trim(Minify_CSS_Compressor::process($css));
                } elseif (class_exists('CSSmin')) {
                    $cssmin = new CSSmin();
                    if (method_exists($cssmin, "run")) {
                        $tmp_code = trim($cssmin->run($css));
                    } elseif (@is_callable(array($cssmin, "minify"))) {
                        $tmp_code = trim(CssMin::minify($css));
                    }
                }
                if (!empty($tmp_code)) {
                    $css = $tmp_code;
                    unset($tmp_code);
                }
                $css = $this->injectMinified($css);
                $this->css_min_arr[$k] = $media . "_scmedia_" . $hash . "_sckey_" . $css;
            }
            unset($css);
        }
        return true;
    }

    //Caches the CSS in uncompressed, deflated and gzipped form.
    public function cache()
    {
        if ($this->group_css == true) {
            // CSS cache
            $cache = new SCMinificationCache($this->hash, 'css');
            if (!$cache->check()) {
                // Cache our code
                $cache->cache($this->hashmap, 'text/css');
            }
            $this->url = SC_JOOMLA_SITE_URL . $cache->getname();
        } else {
            foreach ($this->css_min_arr as $k => $value) {
                $media = substr($value, 0, strpos($value, '_scmedia_'));
                $code = substr($value, strpos($value, '_scmedia_') + strlen('_scmedia_'));
                $hash = substr($code, 0, strpos($code, '_sckey_'));
                $css = substr($code, strpos($code, '_sckey_') + strlen('_sckey_'));
                $cache = new SCMinificationCache($hash, 'css');
                if (!$cache->check()) {
                    // Cache our code
                    $cache->cache($css, 'text/css');
                }
                $group = $media . "_scmedia_" . $hash . "_sckey_" . SC_JOOMLA_SITE_URL . $cache->getname();
                $this->url_group_arr[$k] = $group;
            }
        }
    }

    /**
     * Returns the content
     * @return mixed|string
     */
    public function getcontent()
    {
        // restore IE hacks
        $this->content = $this->restoreIehacks($this->content);
        // restore comments
        $this->content = $this->restoreComments($this->content);
        // restore (no)script
        if (strpos($this->content, '%%SCRIPT%%') !== false) {
            $this->content = preg_replace_callback(
                '#%%SCRIPT%%(.*?)%%SCRIPT%%#is',
                function ($matches) {
                    return base64_decode($matches[1]);
                },
                $this->content
            );
        }

        // restore noptimize
        $this->content = $this->restoreNoptimize($this->content);

        //Restore the full content
        if (!empty($this->restofcontent)) {
            $this->content .= $this->restofcontent;
            $this->restofcontent = '';
        }
        // Inject the new stylesheets
        $replaceTag = array("<title", "before");

        if ($this->defer == true) {
            $img = SC_JOOMLA_SITE_URL . 'plugins/system/speedcache/minifications/load-wait.gif';
            $loader = '<div id="sc-loading" style="width: 100%;
                       height: 100%;
                       top: 0;
                       left: 0;
                       position: fixed;
                       display: block;
                       opacity: 0.9;
                       background-color: #fff;
                       z-index: 99;
                       transition: visibility 0s, opacity 0.1s linear;">
                      <img id="sc-loading-image" style="position: absolute;
                          top: 200px;
                          left: 50%;
                          z-index: 100;" src="'.$img.'" alt="Loading..." />
                    </div>';
            if (preg_match('#(<body).*(>)#Usmi', $this->content, $source)) {
                $this->injectInHtml($loader, array($source[0], 'after'));
            }
        }

        if ($this->defer == true) {
            $deferredCssBlock = "<script type='text/javascript'>function lCss(url,media) {";
            $deferredCssBlock .= "var d=document;var l=d.createElement('link');l.rel='stylesheet';";
            $deferredCssBlock .= "l.type='text/css';l.href=url;l.media=media;";
            $deferredCssBlock .= "aoin=d.getElementsByTagName('noscript')[0];";
            $deferredCssBlock .= "aoin.parentNode.insertBefore(l,aoin.nextSibling);}function deferredCSS() {";
            $noScriptCssBlock = "<noscript>";
        }
        if ($this->group_css == true) {
            //Add the stylesheet either deferred (import at bottom) or normal links in head
            if ($this->defer == true) {
                $deferredCssBlock .= "lCss('" . $this->url . "','all');";
                $noScriptCssBlock .= '<link type="text/css" media="all"';
                $noScriptCssBlock .= '" href="' . $this->url . '" rel="stylesheet" />';
            } else {
                $this->injectInHtml(
                    '<link type="text/css" media="all" href="' . $this->url . '" rel="stylesheet" />',
                    $replaceTag
                );
            }
        } else {
            foreach ($this->url_group_arr as $k => $value) {
                $media = substr($value, 0, strpos($value, '_scmedia_'));
                $code = substr($value, strpos($value, '_scmedia_') + strlen('_scmedia_'));
                $hash = substr($code, 0, strpos($code, '_sckey_'));
                $url = substr($code, strpos($code, '_sckey_') + strlen('_sckey_'));

                $cache = new SCMinificationCache($hash, 'css');
                if ($cache->check()) {
                    $csscode = $cache->retrieve();
                }
                //Add the stylesheet either deferred (import at bottom) or normal links in head
                if ($media == 'font') {
                    $cssInjec = '<link type="text/css" href="' . $url . '" rel="stylesheet" />';
                    $this->injectCssMinifyToHtml($k, $cssInjec);
                } else {
                    if ($this->defer == true) {
                        $deferredCssBlock .= "lCss('" . $url . "','" . $media . "');";
                        $noScriptCssBlock .= '<link type="text/css" media="' . $media ;
                        $noScriptCssBlock .= '" href="' . $url . '" rel="stylesheet" />';
                    } else {
                        if (!empty($csscode)) {
                            if (strlen($csscode) > $this->cssinlinesize) {
                                $cssInjec = '<link type="text/css" media="' . $media ;
                                $cssInjec .= '" href="' . $url . '" rel="stylesheet" />';
                            } elseif (strlen($csscode) > 0) {
                                $cssInjec = '<style type="text/css" media="' . $media . '">' . $csscode . '</style>';
                            }
                            $this->injectCssMinifyToHtml($k, $cssInjec);
                        }
                    }
                }
            }

            // Defer file css without minify
            if (!empty($this->css_notminify_to_defer)) {
                foreach ($this->css_notminify_to_defer as $value) {
                    $media = substr($value, 0, strpos($value, '_sccssdefer_'));
                    $url = substr($value, strpos($value, '_sccssdefer_') + strlen('_sccssdefer_'));
                    $deferredCssBlock .= "lCss('" . $url . "','" . $media . "');";
                    $noScriptCssBlock .= '<link type="text/css" media="' . $media ;
                    $noScriptCssBlock .= '" href="' . $url . '" rel="stylesheet" />';
                }
            }
        }
        if ($this->defer == true) {
            $deferredCssBlock .= "setTimeout(function() {
            document.getElementById('sc-loading').style.visibility = 'hidden';
            },1000);}  var raf = (window.requestAnimationFrame || window.mozRequestAnimationFrame ||
          window.webkitRequestAnimationFrame || window.msRequestAnimationFrame);
      if (raf) {raf(function() {window.setTimeout(deferredCSS, 0); });}
      else {window.addEventListener('load', deferredCSS);}</script>";
            $noScriptCssBlock .= "</noscript>";
            $this->injectInHtml($noScriptCssBlock, $replaceTag);
            $this->injectInHtml($deferredCssBlock, array('</head>', 'before'));
        }
        //Return the modified stylesheet
        return $this->content;
    }

    /**
     * @param $file
     * @param $code
     * @return mixed
     */
    public static function fixurls($file, $code)
    {
        $file = str_replace(JPATH_ROOT, '/', $file);

        $dir = dirname($file); //Like

        // quick fix for import-troubles in e.g. arras theme
        $code = preg_replace('#@import ("|\')(.+?)\.css("|\')#', '@import url("${2}.css")', $code);

        if (preg_match_all('#url\((?!data)(?!\#)(?!"\#)(.*)\)#Usi', $code, $matches)) {
            $replace = array();
            foreach ($matches[1] as $k => $url) {
                // Remove quotes
                $url = trim($url, " \t\n\r\0\x0B\"'");
                $noQurl = trim($url, "\"'");
                if ($url !== $noQurl) {
                    $removedQuotes = true;
                } else {
                    $removedQuotes = false;
                }
                $url = $noQurl;
                if (substr($url, 0, 1) == '/' || preg_match('#^(https?://|ftp://|data:)#i', $url)) {
                    //URL is absolute
                    continue;
                } else {
                    // relative URL
                    $newurl = preg_replace(
                        '/https?:/',
                        '',
                        str_replace(" ", "%20", SC_JOOMLA_SITE_URL . str_replace('//', '/', $dir . '/' . $url))
                    );

                    $hash = md5($url);
                    $code = str_replace($matches[0][$k], $hash, $code);

                    if (!empty($removedQuotes)) {
                        $replace[$hash] = 'url(\'' . $newurl . '\')';
                    } else {
                        $replace[$hash] = 'url(' . $newurl . ')';
                    }
                }
            }
            //Do the replacing here to avoid breaking URLs
            $code = str_replace(array_keys($replace), array_values($replace), $code);
        }
        return $code;
    }

    /**
     * Check exclude css
     * @param $tag
     * @return bool
     */
    private function ismovable($tag)
    {
        if (is_array($this->dontmove)) {
            foreach ($this->dontmove as $match) {
                if (strpos($tag, $match) !== false) {
                    //Matched something
                    return false;
                }
            }
        }
        //If we're here it's safe to move
        return true;
    }

    /**
     * @param $cssPath
     * @param $css
     * @return bool
     */
    private function canInjectLate($cssPath, $css)
    {
        if ((strpos($cssPath, "min.css") === false) || ($this->inject_min_late !== true)) {
            // late-inject turned off or file not minified based on filename
            return false;
        } elseif (strpos($css, "@import") !== false) {
            // can't late-inject files with imports as those need to be aggregated
            return false;
        } else {
            // phew, all is safe, we can late-inject
            return true;
        }
    }
}
