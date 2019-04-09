<?php
/*
 *  Based on some work of autoptimize plugin 
 */
defined('_JEXEC') or die;

/**
 * Class SCMinificationCache
 */
class SCMinificationCache
{
    private $filename;
    private $cachedir;

    /**
     * SCMinificationCache constructor.
     * @param $md5
     * @param string $ext
     */
    public function __construct($md5, $ext = 'php')
    {
        $this->cachedir = SC_JPATH_CACHE_PATH;

        if (in_array($ext, array("js", "css"))) {
            $this->filename = $ext . '/' . SC_CACHEFILE_PREFIX . $md5 . '.' . $ext;
        } else {
            $this->filename = '/' . SC_CACHEFILE_PREFIX . $md5 . '.' . $ext;
        }
    }

    /**
     * Check cache directory
     * @return bool
     */
    public function check()
    {
        if (!file_exists($this->cachedir . $this->filename)) {
            // No cached file, sorry
            return false;
        }
        // Cache exists!
        return true;
    }

    /**
     * Get cache exist
     * @return bool|string
     */
    public function retrieve()
    {
        if ($this->check()) {
            return file_get_contents($this->cachedir . $this->filename);
        }
        return false;
    }

    /**
     * Execute cache js, css
     * @param $code
     * @param $mime
     */
    public function cache($code, $mime)
    {
        if (!$this->check()) {
            // Write code to cache without doing anything else
            file_put_contents($this->cachedir . $this->filename, $code, LOCK_EX);
        }
    }

    /**
     * @return string
     */
    public function getname()
    {
        $name = 'cache/speedcache-minify/' . $this->filename;
        return $name;
    }

    /**
     * Create folder cache
     * @return bool
     */
    public static function createCacheMinificationFolder()
    {
        if (!defined('SC_JPATH_CACHE_PATH')) {
            // We didn't set a cache
            return false;
        }
        foreach (array("", "js", "css") as $checkDir) {
            if (!SCMinificationCache::checkCacheDir(SC_JPATH_CACHE_PATH . $checkDir)) {
                return false;
            }
        }
        /** write index.html here to avoid prying eyes */
        $indexFile = SC_JPATH_CACHE_PATH . 'index.html';
        if (!is_file($indexFile)) {
            @file_put_contents(
                $indexFile,
                '<html><head><meta name="robots" content="noindex, nofollow"></head><body></body></html>'
            );
        }

        /** write .htaccess here  */
        $htAccess = SC_JPATH_CACHE_PATH . '.htaccess';
        if (!is_file($htAccess)) {
            $htAccessContent = '<IfModule mod_headers.c>
                            Header set Vary "Accept-Encoding"
                            Header set Cache-Control "max-age=10672000, must-revalidate"
                    </IfModule>
                    <IfModule mod_expires.c>
                            ExpiresActive On
                            ExpiresByType text/css A30672000
                            ExpiresByType text/javascript A30672000
                            ExpiresByType application/javascript A30672000
                    </IfModule>
                    <IfModule mod_deflate.c>
                        <FilesMatch "\.(js|css)$">
                            SetOutputFilter DEFLATE
                        </FilesMatch>
                    </IfModule>
                    <IfModule mod_authz_core.c>
                        <Files *.php>
                            Require all denied
                        </Files>
                    </IfModule>
                    <IfModule !mod_authz_core.c>
                        <Files *.php>
                            Order deny,allow
                            Deny from all
                        </Files>
                    </IfModule>';

            @file_put_contents($htAccess, $htAccessContent);
        }
        // All OK
        return true;
    }

    /**
     * check dir cache
     * @param $dir
     * @return bool
     */
    public static function checkCacheDir($dir)
    {
        // Check and create if not exists
        if (!file_exists($dir)) {
            @mkdir($dir, 0775, true);
            if (!file_exists($dir)) {
                return false;
            }
        }

        // check if we can now write
        if (!is_writable($dir)) {
            return false;
        }

        // and write index.html here to avoid prying eyes
        $indexFile = $dir . '/index.html';
        if (!is_file($indexFile)) {
            @file_put_contents(
                $indexFile,
                '<html><head><meta name="robots" content="noindex, nofollow"></head><body></body></html>'
            );
        }

        return true;
    }

    /**
     * Remove minification cache
     * @return bool
     */
    public static function clearMinification()
    {
        if (!SCMinificationCache::createCacheMinificationFolder()) {
            return false;
        }

        // scan the cachedirs
        foreach (array("", "js", "css") as $scandirName) {
            $scan[$scandirName] = scandir(SC_JPATH_CACHE_PATH . $scandirName);
        }

        // clear the cachedirs
        foreach ($scan as $scandirName => $scanneddir) {
            $thisAoCacheDir = rtrim(SC_JPATH_CACHE_PATH . $scandirName, "/") . "/";
            foreach ($scanneddir as $file) {
                if (!in_array($file, array('.', '..')) &&
                    strpos($file, SC_CACHEFILE_PREFIX) !== false &&
                    is_file($thisAoCacheDir . $file)) {
                    @unlink($thisAoCacheDir . $file);
                }
            }
        }

        @unlink(SC_JPATH_CACHE_PATH . "/.htaccess");

        return true;
    }

    /**
     * @return SCMinificationCache
     */
    public static function factory()
    {

        static $instance;

        if (!$instance) {
            $instance = new self();
        }

        return $instance;
    }
}
