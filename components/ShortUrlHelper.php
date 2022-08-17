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
        $pos = strpos(self::$base, $index);
        if (false === $pos || !isset(self::$baseList[$pos])) {
            return null;
        }
        $id = Base62::decode($code, self::$baseList[$pos]);
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
     * 检测非法ip
     * @return bool 检测异常返回false
     */
    public static function banipCheck()
    {
        if (!config('enable-banip')) return true;
        $tableName = config('table-name-banip');
        $stmt = pdo()->prepare("SELECT time,fail_count FROM `$tableName` WHERE ip=:ip LIMIT 1");
        $ip = getRemoteAddr();
        $stmt->execute([':ip' => $ip]);
        $row = $stmt->fetch();
        if (!$row) {
            return true;
        }
        if ($row['fail_count'] < config('banip-fail-count')) {
            return true;
        }
        if (strtotime($row['time']) < time() - config('banip-time')) {
            self::banipDelete();
            return true;
        }
        return false;
    }

    /**
     * 记录非法ip
     */
    public static function banipSave()
    {
        if (!config('enable-banip')) return true;
        $tableName = config('table-name-banip');
        $stmt = pdo()->prepare("SELECT id,time,fail_count FROM `$tableName` WHERE ip=:ip LIMIT 1");
        $ip = getRemoteAddr();
        $stmt->execute([':ip' => $ip]);
        $row = $stmt->fetch();
        if ($row) {
            $stmt = pdo()->prepare("UPDATE `$tableName` SET `fail_count`=`fail_count`+1,`time`=NOW() WHERE id=:id LIMIT 1");
            $stmt->execute([':id' => $row['id']]);
            return;
        }
        $stmt = pdo()->prepare("INSERT INTO `$tableName` (ip,fail_count,time) VALUES(:ip,1,NOW())");
        $stmt->execute([':ip' => $ip]);
    }

    /**
     * 解禁非法ip
     */
    public static function banipDelete()
    {
        if (!config('enable-banip')) return true;
        $tableName = config('table-name-banip');
        $stmt = pdo()->prepare("UPDATE `$tableName` SET `fail_count`=0,`time`=NOW() WHERE ip=:ip LIMIT 1");
        $ip = getRemoteAddr();
        $stmt->execute([':ip' => $ip]);
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
