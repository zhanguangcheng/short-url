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
    'table-name-banip' => 'short_banip',

    // 是否启用禁用ip功能，防止暴力破解
    'enable-banip' => true,
    'banip-fail-count' => 10,// 失败次数，达到后将禁用该ip
    'banip-time' => 600,// 禁用时间，单位：秒

    // 是否启用权限验证，用于内部生成短网址
    'enable-auth' => false,
    'auth-users' => [
        // 账号 => 密码
        'demo' => 'demo123',
    ],

    // 域名
    'website-host' => 'http://short.cn',
];
