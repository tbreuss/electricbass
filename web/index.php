<?php

// hack for local development together with traefik and tls
if (isset($_SERVER['HTTP_HOST']) && str_contains($_SERVER['HTTP_HOST'], 'electricbass.test')) {
    if (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && ($_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https')) {
        $_SERVER['HTTPS'] = 'on';
    }
}

require(__DIR__ . '/../vendor/autoload.php');

$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

// comment out the following two lines when deployed to production
defined('YII_DEBUG') or define('YII_DEBUG', ($_ENV['YII_DEBUG'] ?? '') === 'true');
defined('YII_ENV') or define('YII_ENV', ($_ENV['YII_ENV'] ?? ''));

require(__DIR__ . '/../vendor/yiisoft/yii2/Yii.php');

$config = require(__DIR__ . '/../config/web.php');

(new yii\web\Application($config))->run();
