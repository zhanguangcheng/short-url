# short-url

短网址生成，纯PHP实现，代码仅5.89 KB，版本要求：PHP >= 5.4

![](screenshot.png)

## 部署步骤

1. 下载项目
```bash
git clone https://github.com/zhanguangcheng/short-url.git
```

1. 生成随机序列  
浏览器访问`http://short.cn/make-random.php`，将结果分别替换掉`components/ShortUrlHelper::$baseList`属性值和`short-url.sql`中的自增编号
2. 导入数据库：`short-url.sql`
3. 配置域名到项目根目录，如`http://short.cn`
4. 配置数据库和域名：`config.php`

## 使用方法
1. 生成短网址
```
http://short.cn/make.php
```
2. 短网址跳转
```
http://short.cn?c=xxxxxxx
```

减短网址的技巧：
1. 优先选择较短的域名
2. 开启Apache或nginx的重写，最终的格式：`http://short.cn/xxxxxxx`