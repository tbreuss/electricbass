<?php

$config = [
    'id' => 'basic',
    'language' => 'de',
    'basePath' => dirname(__DIR__),
    'bootstrap' => [
        'log'
    ],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'controllerMap' => [
        'alpha-tab' => 'app\feature\alphaTab\WebController',
    ],
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => ($_ENV['COOKIE_VALIDATION_KEY'] ?? ''),
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
        ],
        'mailer' => require(__DIR__ . '/mailer.php'),
        'log' => require(__DIR__ . '/log.php'),
        'db' => require(__DIR__ . '/db.php'),
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'enableStrictParsing' => true,
            'normalizer' => [
                'class' => 'yii\web\UrlNormalizer',
                'collapseSlashes' => true,
                'normalizeTrailingSlash' => true,
                'action' => null,
            ],
            'rules' => require(__DIR__ . '/rules.php'),
        ],
        'shortcode' => [
            'class' => 'app\components\Shortcode',
            'tags' => [
                'alphatab' => ['app\widgets\Parser', 'alphatab'],
                'amazon' => ['app\widgets\Parser', 'amazon'],
                'articles' => ['app\widgets\Parser', 'articles'],
                'downloads' => ['app\widgets\Parser', 'downloads'],
                'htmlphp' => ['app\widgets\Parser', 'htmlphp'],
                'img' => ['app\widgets\Parser', 'image'],
                'imgtext' => ['app\widgets\Parser', 'imgtext'],
                'links' => ['app\feature\links\Shortcode', 'render'],
                'jsongallery' => ['app\widgets\Parser', 'jsongallery'],
                'jsonlinks' => ['app\widgets\Parser', 'jsonlinks'],
                'lessonnav' => ['app\widgets\Parser', 'lessonnav'],
                'rssfeed' => ['app\widgets\Parser', 'rssfeed'],
                'score' => ['app\widgets\Parser', 'score'],
                'soundcloud' => ['app\widgets\Parser', 'soundcloud'],
                'spotify' => ['app\widgets\Parser', 'spotify'],
                'vimeo' => ['app\widgets\Parser', 'vimeo'],
                'websites' => ['app\widgets\Parser', 'websites'],
                'youtube' => ['app\widgets\Parser', 'youtube'],
                'play-along' => ['app\widgets\Parser', 'playAlong'],
            ],
        ],
        'i18n' => [
            'translations' => [
                'app*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    //'basePath' => '@app/messages',
                    //'sourceLanguage' => 'en-US',
                    'fileMap' => [
                        'app' => 'app.php',
                    ],
                ],
            ],
        ],
        'assetManager' => [
            'class' => 'yii\web\AssetManager',
            'appendTimestamp' => true,
            'hashCallback' => function ($path) {
                $prefix = (string)Yii::getAlias('@app/');
                if (str_starts_with($path, $prefix)) {
                    $path = substr($path, strlen($prefix));
                }
                return $path;
            }
        ],
        'formatter' => [
            'sizeFormatBase' => 1000,
            'thousandSeparator' => "'",
            'decimalSeparator' => '.'
        ],
    ],
    'modules' => [
        'admin' => [
            'class' => 'app\modules\admin\Module',
        ],
    ],
    'params' => require(__DIR__ . '/params.php'),
];

/* @phpstan-ignore-next-line */
if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        'allowedIPs' => ['127.0.0.1', '::1', '172.18.0.*']
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        'allowedIPs' => ['127.0.0.1', '::1', '172.18.0.*']
    ];
}

return $config;
