<?php

define("SAFE_FLAG", true);
require_once __DIR__ . '/init.php';

$code = get('c');
if ($code) {
    $model = ShortUrlHelper::find($code);
    if ($model) {
        ShortUrlHelper::redirect($model);
        exit(0);
    }
}
echo '信息不存在';
exit(1);
