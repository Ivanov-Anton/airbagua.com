CREATE TABLE IF NOT EXISTS `#__speedcache_urls` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `url` varchar(255) NOT NULL,
    `cacheguest` tinyint(1) NOT NULL,
    `cachelogged` tinyint(1) NOT NULL,
    `preloadguest` tinyint(1) NOT NULL,
    `preloadlogged` tinyint(1) NOT NULL,
    `preloadperuser` tinyint(1) NOT NULL,
    `lifetime` tinyint(1) NOT NULL,
    `specifictime` int(10) NOT NULL,
    `excludeguest` tinyint(1) NOT NULL,
    `excludelogged` tinyint(1) NOT NULL,
    `ignoreparams` tinyint(1) NOT NULL,
    `type` varchar(50) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;

CREATE TABLE IF NOT EXISTS `#__speedcache_minify_file` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `file` varchar(255) NOT NULL UNIQUE ,
    `minify` tinyint(2) NOT NULL,
    `type` tinyint(3) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;
