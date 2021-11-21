<?php

/**
 * 短网址Helper
 */
class ShortUrlHelper
{
    /**
     * 基础序列
     * @var string
     */
    private static $base = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    /**
     * 62组随机序列，可以使用makeBaseList生成
     * @var array
     */
    private static $baseList = [];

    /**
     * 保存网址
     * @param  string $url 原始网址
     * @return string 生成好的7位短码
     */
    public static function save($url)
    {
        $pdo = pdo();
        $tableName = config('table-name');
        $crc32 = sprintf('%u', crc32($url));
        $stmt = $pdo->prepare("SELECT id FROM `$tableName` WHERE `crc32`=:crc32 AND `url`=:url LIMIT 1");
        $stmt->execute([':crc32' => $crc32, ':url' => $url]);
        $result = $stmt->fetch();
        if (!$result) {
            $stmt = $pdo->prepare("INSERT INTO `$tableName` (`crc32`, `url`) VALUES(:crc32, :url)");
            $stmt->execute([':crc32' => $crc32, ':url' => $url]);
            $id = $pdo->lastInsertId();
        } else {
            $id = $result['id'];
        }
        $index = bcmod($crc32, 62);
        return self::$base[$index] . Base62::encode($id, self::$baseList[$index]);
    }

    /**
     * 使用短码查找网址信息
     * @param  string $code 7位短码
     * @return array|null
     */
    public static function find($code)
    {
        $index = substr($code, 0, 1);
        $code = substr($code, 1);
        $id = Base62::decode($code, self::$baseList[strpos(self::$base, $index)]);
        if ($id) {
            $tableName = config('table-name');
            $stmt = pdo()->prepare("SELECT `id`,`url` FROM `$tableName` WHERE `id`=:id LIMIT 1");
            $stmt->execute([':id' => $id]);
            return $stmt->fetch();
        }
    }

    /**
     * 执行跳转
     * @param array
     */
    public static function redirect($model)
    {
        $tableName = config('table-name');
        $stmt = pdo()->prepare("UPDATE `$tableName` SET `view_count`=`view_count`+1 WHERE `id`=:id LIMIT 1");
        $stmt->execute([':id' => $model['id']]);
        header('Location: ' . $model['url'], true, 301);
    }

    /**
     * 生成62组随机序列
     * @return array 62组随机序列
     */
    public static function makeBaseList()
    {
        $result = [];
        for ($i = 0; $i < 62; $i++) {
            $result[] = str_shuffle(self::$base);
        }
        return $result;
    }
}
