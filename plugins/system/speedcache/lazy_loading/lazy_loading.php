<?php

/*
 * Using lazy-loading-xt jquery plugin
 */

/**
 * Class SCLazyLoading
 */
class SCLazyLoading {
    private $content;
    /**
     * SCLazyLoading constructor.
     * @param $contents
     */
    public function __construct($contents)
    {
        $this->content = $contents;
    }

    /**
     * Set lazyloading image on contents
     * @return mixed
     */
    public function setup()
    {
        // Get img in script to array to check
        $imginscript = array();
        if (preg_match_all('#<script(.*?)>(.*?)</script>#is', $this->content, $matches)){
            foreach ($matches[0] as $scripttag) {
                if (preg_match_all('#<img\s[^>]*?src\s*=\s*[\'\"]([^\'\"]*?)[\'\"][^>]*?>#Usmi', $scripttag, $matches1)) {
                    foreach ($matches1[0] as $imgin) {
                        $imginscript[] = $imgin;
                    }
                }
            }
        }
        if (preg_match_all('#<img\s[^>]*?src\s*=\s*[\'\"]([^\'\"]*?)[\'\"][^>]*?>#Usmi', $this->content, $matches)) {
            $placeholder_url = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/x8AAwMCAO+ip1sAAAAASUVORK5CYII=';

            foreach ($matches[0] as $imgtag) {
                //Cancel the img tag in the script
                if (in_array($imgtag, $imginscript)) {
                    continue;
                }
                if (!preg_match("/src=['\"]data:image/is", $imgtag)) {
                    // replace the src and add the data-src attribute
                    $imgtag_data = preg_replace(
                        '/<img(.*?)src=/is',
                        '<img$1src="' . $placeholder_url . '" data-speedcachelazy-src=',
                        $imgtag
                    );

                    // replace the srcset
                    $imgtag_data = str_replace('srcset', 'data-speedcachelazy-srcset', $imgtag_data);
                    // add the lazy class to the img element
                    if (preg_match('/class=["\']/i', $imgtag_data)) {
                        $imgtag_data = preg_replace(
                            '/class=(["\'])(.*?)["\']/is',
                            'class=$1speedcache-lazy speedcache-lazy-hidden $2$1',
                            $imgtag_data
                        );
                    } else {
                        $imgtag_data = preg_replace(
                            '/<img/is',
                            '<img class="speedcache-lazy speedcache-lazy-hidden"',
                            $imgtag_data
                        );
                    }
                    $noscript = "<noscript>" . $imgtag . "</noscript>";
                    $imgtag_data .= $noscript;

                    // Replace new img tag to old img tag
                    $this->content = str_replace($imgtag, $imgtag_data, $this->content);
                }
            }
        }
        return $this->content;
    }
}
