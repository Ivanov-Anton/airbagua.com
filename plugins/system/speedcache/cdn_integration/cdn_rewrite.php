<?php


/**
 * Class SCCDNRewrite
 */
class SCCDNRewrite {
    private $blog_url = array();
    private $cdn_url = null;
    private $dirs = array();
    private $excludes = array();
    private $relative = false;


    /**
     * Get CDN parameter from option
     * @param $option
     */
    public function __construct()
    {
        $params = JComponentHelper::getParams('com_speedcache');
        $this->blog_url = array(
            rtrim(JURI::root(), "/"),
            JURI::root(true)
        );
        $this->cdn_url = $params->get('cdn_url', '');
        $this->dirs = explode(',', $params->get('cdn_content', 0));
        $this->excludes = explode(',', $params->get('cdn_exclude_content', 0));
        $this->relative = $params->get('cdn_relative_path', 0);
    }

    /**
     * Replace cdn on html raw
     * @param $content
     * @return mixed
     */
    public function rewrite($content)
    {
        if (empty($this->cdn_url)) {
            return $content;
        }

        if ($this->cdn_url == $this->blog_url[0] || $this->cdn_url == $this->blog_url[1]) {
            return $content;
        }

        $blog_url = '('. implode('|', array_map('quotemeta', array_map('trim', $this->blog_url))) .')';

        // get dir scope in regex format
        $dirs = $this->getDirScope();

        // regex rule start
        $regex_rule = '#(?<=[(\"\'])';

        // check if relative paths
        if ($this->relative) {
            $regex_rule .= '(?:' . $blog_url . ')?';
        } else {
            $regex_rule .= $blog_url;
        }

        // regex rule end
        $regex_rule .= '/(?:((?:' . $dirs . ')[^\"\')]+)|([^/\"\']+\.[^/\"\')]+))(?=[\"\')])#';

        // call the cdn rewriter callback
        $new_content = preg_replace_callback($regex_rule, array(&$this, 'replaceCdnUrl'), $content);

        return $new_content;
    }

    /**
     * Replace cdn url to root url
     * @param $match
     * @return mixed
     */
    protected function replaceCdnUrl($match)
    {
        //return file type or directories excluded
        if ($this->excludesCheck($match[0])) {
            return $match[0];
        }

        if (strpos($match[0], '/') === 0) {
            // Check blog without host
            return $this->cdn_url . $match[0];
        } else {
            $parseUrl = parse_url($this->blog_url[0]);
            $scheme = 'http://';
            if (isset($parseUrl['scheme'])) {
                $scheme = $parseUrl['scheme'] . '://';
            }
            $host = $parseUrl['host'];
            //get domain
            $domain = $scheme . $host;
            // check if not a relative path
            if (!$this->relative || strstr($match[0], $this->blog_url[0])) {
                return str_replace($domain, $this->cdn_url, $match[0]);
            }
        }

        // Relative path
        $pattern = '@(\/\/)?' . $host . '@';
        return preg_replace($pattern, $this->cdn_url, $match[0]);
    }

    /**
     * Check excludes assets
     * @param $dir
     * @return bool
     */
    protected function excludesCheck($dir)
    {
        if (!empty($this->excludes)) {
            foreach ($this->excludes as $exclude) {
                if (stristr($dir, $exclude) != false) {
                    return true;
                }
            }
        }
        return false;
    }


    /**
     * get directory scope
     */

    protected function getDirScope()
    {
        // default
        if (empty($this->dirs) || count($this->dirs) < 1) {
            return 'media|templates';
        }

        return implode('|', array_map('quotemeta', array_map('trim', $this->dirs)));
    }
}
