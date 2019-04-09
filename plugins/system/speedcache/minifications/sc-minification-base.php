<?php
/* 
 *  Based on some work of autoptimize plugin 
 */
defined('_JEXEC') or die;

/**
 * Class SCMinificationBase
 */
abstract class SCMinificationBase
{
    protected $content = '';
    protected $tagWarning = false;

    /**
     * SCMinificationBase constructor.
     * @param $content
     */
    public function __construct($content)
    {
        $this->content = $content;
    }

    /**
     * Reads the page and collects tags
     * @param $justhead
     * @return mixed
     */
    abstract public function read($justhead);

    //Joins and optimizes collected things
    abstract public function minify();

    //Caches the things
    abstract public function cache();

    //Returns the content
    abstract public function getcontent();

    /**
     * Converts an URL to a full path
     * @param $url
     * @return bool|mixed|string
     */
    protected function getpath($url)
    {
        if (strpos($url, '%') !== false) {
            $url = urldecode($url);
        }
        // normalize
        if (strpos($url, '//') === 0) {
            if (self::isSsl()) {
                $url = "https:" . $url;
            } else {
                $url = "http:" . $url;
            }
        }

        $domain = ((self::isSsl()) ? "https://" : "http://") . $_SERVER['HTTP_HOST'];
        if (strpos($url, 'http') === false) {
            $url = $domain . $url;
        }

        if (strpos($url, $_SERVER['HTTP_HOST']) === false) {
            //is external url, for example: http://fonts.googleapis.com/css
            return false;
        }

        // try to remove "joomla root url" from url while not minding http<>https

        $path = str_replace(SC_JOOMLA_SITE_URL, '/', $url);

        $path = JPATH_BASE . $path;

        return $path;
    }

    /**
     * Converts a path to an URL
     */
    protected function getUrl($path)
    {
        if (strpos($path, '%') !== false) {
            $path = urlencode($path);
        }
        if (strpos($path, $_SERVER['HTTP_HOST']) !== false) {
            return false;
        }

        if (strpos($path, JPATH_BASE) === false) {
            return false;
        }

        $url = str_replace(JPATH_BASE, SC_JOOMLA_SITE_URL, $path);

        return $url;
    }
    /**
     * needed for filter
     * @param $in
     * @return mixed|string
     */
    protected function aoGetDomain($in)
    {
        // make sure the url starts with something vaguely resembling a protocol
        if ((strpos($in, "http") !== 0) && (strpos($in, "//") !== 0)) {
            $in = "http://" . $in;
        }

        // do the actual parse_url
        $out = parse_url($in, PHP_URL_HOST);

        // fallback if parse_url does not understand the url is in fact a url
        if (empty($out)) {
            $out = $in;
        }

        return $out;
    }

    /**
     * logger
     * @param $logmsg
     * @param bool $appendHTML
     */
    protected function aoLogger($logmsg, $appendHTML = true)
    {
        if ($appendHTML) {
            $logmsg = "<!--noptimize--><!-- " . $logmsg . " --><!--/noptimize-->";
            $this->content .= $logmsg;
        } else {
            error_log("Error: " . $logmsg);
        }
    }

    /**
     * Hide everything between noptimize-comment tags
     * @param $noptimize_in
     * @return mixed
     */
    protected function hideNoptimize($noptimize_in)
    {
        if (preg_match('/<!--\s?noptimize\s?-->/', $noptimize_in)) {
            $noptimize_out = preg_replace_callback(
                '#<!--\s?noptimize\s?-->.*?<!--\s?/\s?noptimize\s?-->#is',
                function ($matches) {
                    return "%%NOPTIMIZE%%".base64_encode($matches[0])."%%NOPTIMIZE%%";
                },
                $noptimize_in
            );
        } else {
            $noptimize_out = $noptimize_in;
        }
        return $noptimize_out;
    }

    /**
     * unhide noptimize-tags
     * @param $noptimize_in
     * @return mixed
     */
    protected function restoreNoptimize($noptimize_in)
    {
        if (strpos($noptimize_in, '%%NOPTIMIZE%%') !== false) {
            $noptimize_out = preg_replace_callback(
                '#%%NOPTIMIZE%%(.*?)%%NOPTIMIZE%%#is',
                function ($matches) {
                    return base64_decode($matches[1]);
                },
                $noptimize_in
            );
        } else {
            $noptimize_out = $noptimize_in;
        }
        return $noptimize_out;
    }

    /**
     * @param $iehacks_in
     * @return mixed
     */
    protected function hideIehacks($iehacks_in)
    {
        if (strpos($iehacks_in, '<!--[if') !== false) {
            $iehacks_out = preg_replace_callback(
                '#<!--\[if.*?\[endif\]-->#is',
                function ($matches) {
                    return "%%IEHACK%%".base64_encode($matches[0])."%%IEHACK%%";
                },
                $iehacks_in
            );
        } else {
            $iehacks_out = $iehacks_in;
        }
        return $iehacks_out;
    }

    /**
     * @param $iehacks_in
     * @return mixed
     */
    protected function restoreIehacks($iehacks_in)
    {
        if (strpos($iehacks_in, '%%IEHACK%%') !== false) {
            $iehacks_out = preg_replace_callback(
                '#%%IEHACK%%(.*?)%%IEHACK%%#is',
                function ($matches) {
                    return base64_decode($matches[1]);
                },
                $iehacks_in
            );
        } else {
            $iehacks_out = $iehacks_in;
        }
        return $iehacks_out;
    }

    /**
     * @param $comments_in
     * @return mixed
     */
    protected function hideComments($comments_in)
    {
        if (strpos($comments_in, '<!--') !== false) {
            $comments_out = preg_replace_callback(
                '#<!--.*?-->#is',
                function ($matches) {
                    return "%%COMMENTS%%".base64_encode($matches[0])."%%COMMENTS%%";
                },
                $comments_in
            );
        } else {
            $comments_out = $comments_in;
        }
        return $comments_out;
    }

    /**
     * @param $comments_in
     * @return mixed
     */
    protected function restoreComments($comments_in)
    {
        if (strpos($comments_in, '%%COMMENTS%%') !== false) {
            $comments_out = preg_replace_callback(
                '#%%COMMENTS%%(.*?)%%COMMENTS%%#is',
                function ($matches) {
                    return base64_decode($matches[1]);
                },
                $comments_in
            );
        } else {
            $comments_out = $comments_in;
        }
        return $comments_out;
    }

    /**
     * @param $payload
     * @param $replaceTag
     */
    protected function injectInHtml($payload, $replaceTag)
    {
        if (strpos($this->content, $replaceTag[0]) !== false) {
            if ($replaceTag[1] === "after") {
                $replaceBlock = $replaceTag[0] . $payload;
            } elseif ($replaceTag[1] === "replace") {
                $replaceBlock = $payload;
            } else {
                $replaceBlock = $payload . $replaceTag[0];
            }
            $this->content = substr_replace(
                $this->content,
                $replaceBlock,
                strpos($this->content, $replaceTag[0]),
                strlen($replaceTag[0])
            );
        } else {
            $this->content .= $payload;
            if (!$this->tagWarning) {
                $this->content .= "<!--noptimize--><!-- SpeedCache found a problem with the HTML in your Theme, tag ";
                $this->content .= $replaceTag[0] . " missing --><!--/noptimize-->";
                $this->tagWarning = true;
            }
        }
    }

    /**
     * @param $k
     * @param $script
     * @return bool
     */
    protected function injectMinifyToHtml($k, $script)
    {
        if (!empty($this->matches)) {
            foreach ($this->matches as $tag) {
                if (strpos($tag, $k) !== false) {
                    $this->content = str_replace($tag, $script, $this->content);
                }
            }
        }
        return true;
    }

    protected function injectCssMinifyToHtml($k, $script)
    {
        if (!empty($this->cssmatches)) {
            foreach ($this->cssmatches as $tag) {
                if (strpos($tag, $k) !== false) {
                    $this->content = str_replace($tag, $script, $this->content);
                }
            }
        }
        return true;
    }
    /**
     * @param $tag
     * @param $removables
     * @return bool
     */
    protected function isremovable($tag, $removables)
    {
        foreach ($removables as $match) {
            if (strpos($tag, $match) !== false) {
                return true;
            }
        }
        return false;
    }

    /**
     * inject already minified code in optimized JS/CSS
     * @param $in
     * @return mixed
     */
    protected function injectMinified($in)
    {
        if (strpos($in, '%%INJECTLATER%%') !== false) {
            $out = preg_replace_callback(
                '#%%INJECTLATER%%(.*?)%%INJECTLATER%%#is',
                function ($matches) {
                    $filepath    = base64_decode(strtok($matches[1], '|'));
                    $filecontent = file_get_contents($filepath);

                    // remove BOM
                    $filecontent = preg_replace('#\x{EF}\x{BB}\x{BF}#', '', $filecontent);

                    // remove comments and blank lines
                    if (substr($filepath, - 3, 3) === '.js') {
                        $filecontent = preg_replace('#^\s*\/\/.*$#Um', '', $filecontent);
                    }

                    $filecontent = preg_replace('#^\s*\/\*[^!].*\*\/\s?#Us', '', $filecontent);
                    $filecontent = preg_replace("#(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+#", "\n", $filecontent);

                    // specific stuff for JS-files
                    if (substr($filepath, - 3, 3) === '.js') {
                        if ((substr($filecontent, - 1, 1) !== ';') && (substr($filecontent, - 1, 1) !== '}')) {
                            $filecontent .= ';';
                        }

                        if (get_option('sc_js_trycatch') === 'on') {
                            $filecontent = 'try{' . $filecontent . '}catch(e){}';
                        }
                    } elseif ((substr($filepath, - 4, 4) === '.css')) {
                        $filecontent = SCMinificationStyles::fixurls($filepath, $filecontent);
                    }

                    // return
                    return "\n" . $filecontent;
                },
                $in
            );
        } else {
            $out = $in;
        }
        return $out;
    }

    /**
     * Determines if SSL is used.
     *
     * @since 2.6.0
     * @since 4.6.0 Moved from functions.php to load.php.
     *
     * @return bool True if SSL, otherwise false.
     */
    public static function isSsl()
    {
        if (isset($_SERVER['HTTPS'])) {
            if ('on' == strtolower($_SERVER['HTTPS'])) {
                return true;
            }

            if ('1' == $_SERVER['HTTPS']) {
                return true;
            }
        } elseif (isset($_SERVER['SERVER_PORT']) && ('443' == $_SERVER['SERVER_PORT'])) {
            return true;
        }
        return false;
    }


    /**
     * Check to exclude url from group
     * @param $url
     * @param $excludes
     * @return bool
     */
    protected function checkExcludeFile($url, $excludes)
    {
        if (!empty($excludes)) {
            foreach ($excludes as $ex) {
                if (empty($ex)) {
                    continue;
                }
                if (strpos($ex, '/') === 0) {
                    $ex = ltrim($ex, '/');
                }
                preg_match_all('@' . $ex . '@', $url, $matches);

                if (!empty($matches[0])) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * Check url exist
     * @param $url
     * @return bool
     */
    protected function checkUrlExist($url)
    {
        stream_context_set_default( [
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
            ],
        ]);
        $file_headers = get_headers($url);
        if (!$file_headers || $file_headers[0] == 'HTTP/1.1 404 Not Found') {
            $status = false;
        } else {
            $status = true;
        }
        return $status;
    }
}
