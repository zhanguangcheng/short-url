<?php

defined("SAFE_FLAG") or exit(1);

$config = require __DIR__ . '/config.php';
require __DIR__ . '/components/Base62.php';
require __DIR__ . '/components/ShortUrlHelper.php';

function pdo()
{
    static $pdo;
    if (!$pdo) {
        global $config;
        $pdo = new PDO($config['dsn'], $config['username'], $config['password']);
        $pdo->exec("SET NAMES {$config['charset']}");
        $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    }
    return $pdo;
}

function config($key = null)
{
    global $config;
    if ($key) {
        return $config[$key];
    }
    return $config;
}

function get($name, $defaultValue = null)
{
    return isset($_GET[$name]) ? $_GET[$name] : $defaultValue;
}

function post($name, $defaultValue = null)
{
    return isset($_POST[$name]) ? $_POST[$name] : $defaultValue;
}
