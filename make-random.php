<?php

define("SAFE_FLAG", true);
require_once __DIR__ . '/init.php';
echo 'private static $baseList = ' . json_encode(ShortUrlHelper::makeBaseList()) . ';';
echo '<hr>';
echo ') ENGINE=InnoDB AUTO_INCREMENT=' . mt_rand(10000, 99999) . mt_rand(10000, 99999) . ' DEFAULT CHARSET=utf8;';