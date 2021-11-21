<?php

defined("SAFE_FLAG") or exit(1);

return [
    // 数据库配置
    'dsn' => 'mysql:host=localhost;dbname=test;charset=utf8',
    'username' => 'root',
    'password' => 'root',
    'charset' => 'utf8',

    // 表名称
    'table-name' => 'short_url',

    // 域名
    'website-host' => 'http://short.cn',
];
