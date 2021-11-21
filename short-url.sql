CREATE TABLE `short_url` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `crc32` int(10) unsigned NOT NULL,
  `url` varchar(500) NOT NULL,
  `view_count` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `crc32` (`crc32`)
) ENGINE=InnoDB AUTO_INCREMENT=2000000000 DEFAULT CHARSET=utf8;