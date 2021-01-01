# zhos framework
```
PHP 简易开发框架
```

## 框架结构
* auth    认证模块
* db      数据库持久层
* http    网络通信层
* log     日志记录
* redis   缓存

## 使用集成
```
<?php
// 应用配置
define('APP_ROOT', dirname(__DIR__));
define('APP_UPLOAD', APP_ROOT . DIRECTORY_SEPARATOR . 'upload');

// 数据库配置
define('DB_DSN', 'mysql:host=localhost;port=3306;dbname=test');
define('DB_USER', '');
define('DB_PASS', '');

date_default_timezone_set("Asia/Shanghai");

// 加载应用文件
$loader = include '../../../zhos/vendor/autoload.php';

use zhos\Zos;
Zos::setName('demo');
$loader->setPsr4(Zos::getName() . '\\', array(APP_ROOT));
Zos::run();
```