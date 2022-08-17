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

function urlCheck($url)
{
    return preg_match('/^(http|https):\/\/(([A-Z0-9][A-Z0-9_-]*)(\.[A-Z0-9][A-Z0-9_-]*)+)(?::\d{1,5})?(?:$|[?\/#])/i', $url);
}

function authValidate()
{
    global $config;
    $users = $config['auth-users'];
    return isset($_SERVER['PHP_AUTH_USER']) 
        && isset($_SERVER['PHP_AUTH_PW']) 
        && isset($users[$_SERVER['PHP_AUTH_USER']])
        && $users[$_SERVER['PHP_AUTH_USER']] === $_SERVER['PHP_AUTH_PW'];
}

function getRemoteAddr()
{
    return sprintf('%u', ip2long($_SERVER['REMOTE_ADDR']));
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

function isAjax()
{
    return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest';
}
