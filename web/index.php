<?php

define("SAFE_FLAG", true);
require_once __DIR__ . '/../init.php';

$code = trim($_SERVER['REQUEST_URI'], '/');
$isValid = preg_match('/^[a-zA-Z\d]{5,}$/', $code);
if (!($code && $isValid)) {
    header('Location: /make.php');
    exit(0);
}

if (!ShortUrlHelper::banipCheck()) {
    echo '非法访问';
    exit(1);
}

$model = ShortUrlHelper::find($code);
if ($model) {
    ShortUrlHelper::redirect($model);
    exit(0);
}

ShortUrlHelper::banipSave();
echo '信息不存在';
exit(1);
