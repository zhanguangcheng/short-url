<?php

define("SAFE_FLAG", true);
require_once __DIR__ . '/init.php';

$shortUrl = '';
$message = '';
$url = post('url');
if ($url) {
    if (preg_match('/^(http|https):\/\/(([A-Z0-9][A-Z0-9_-]*)(\.[A-Z0-9][A-Z0-9_-]*)+)(?::\d{1,5})?(?:$|[?\/#])/i', $url)) {
        $shortUrl = $config['website-host'] . '/' . ShortUrlHelper::save($url);
    } else {
        $message = '网址格式不正确';
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>短网址生成</title>
    <style>
        .container {
            width:1200px;
            margin:0 auto;
        }
        .input {
            display: flex;
            margin-top: 50px;
            margin-bottom: 20px;
        }
        .input input {
            padding: 0 10px;
            width:1000px;
            height:38px;
            line-height: 38px;
            border-radius: 4px 0 0 4px;
            border: 1px solid #aaa;
            float:left;
            outline: none;
        }
        .input input:focus {
            border-color: #4e6ef2;
        }
        .input button {
            height:40px;
            line-height: 40px;
            padding: 0 10px;
            border:1px solid #4e6ef2;
            background-color: #4e6ef2;
            border-radius: 0 4px 4px 0;
            color:#fff;
            float:left;
        }
        .input button:hover {
            background-color: #4662d9;
        }
        .result {
            background-color: #d9edf7;
            border-radius: 4px;
            padding:15px;
        }
        .message {
            background-color: #f2dede;
            border-radius: 4px;
            padding:15px;
        }
    </style>
</head>

<body>
    <div class="container">
        <form method="post">
            <div class="input">
                <input name="url" type="text" placeholder="请输入网址">
                <button type="submit">生成短网址</button>
            </div>
            <?php if ($shortUrl) : ?>
                <div class="result">
                    短网址：<span><?= $shortUrl ?></span>
                </div>
            <?php elseif ($message) : ?>
                <p class="message"><?= $message ?></p>
            <?php endif ?>
        </form>
    </div>
</body>

</html>