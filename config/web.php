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
    'layoutPath' => '@app/layouts/web',
    'controllerMap' => [
        'advertisement' => app\features\advertisement\Controller::class,
        'album' => app\features\album\Controller::class,
        'alpha-tab' => app\features\alphaTab\Controller::class,
        'api' => app\features\api\Controller::class,
        'blog' => app\features\blog\Controller::class,
        'catalog' => app\features\catalog\Controller::class,
        'comment' => app\features\comment\Controller::class,
        'contact' => app\features\contact\Controller::class,
        'error' => app\features\error\Controller::class,
        'feed' => app\features\feed\Controller::class,
        'fingering' => app\features\fingering\Controller::class,
        'glossar' => app\features\glossar\Controller::class,
        'homepage' => app\features\homepage\Controller::class,
        'joke' => app\features\joke\Controller::class,
        'lesson' => app\features\lesson\Controller::class,
        'metronome' => app\features\metronome\Controller::class,
        'music-paper' => app\features\musicPaper\Controller::class,
        'quote' => app\features\quote\Controller::class,
        'search' => app\features\search\Controller::class,
        'sitemap' => app\features\sitemap\Controller::class,
        'test' => app\features\test\Controller::class,
        'video' => app\features\video\Controller::class,
        'youtube' => app\features\youtube\Controller::class,
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
            'errorAction' => 'error/index',
        ],
        'user' => [
            'identityClass' => 'app\components\User',
        ],
        'mailer' => require(__DIR__ . '/mailer.php'),
        'log' => require(__DIR__ . '/log.php'),
        'db' => require(__DIR__ . '/db.php'),
        'urlManager' => [
            'hostInfo' => $_ENV['HOST_INFO'],
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'enableStrictParsing' => true,
            'normalizer' => [
                'class' => 'yii\web\UrlNormalizer',
                'collapseSlashes' => true,
                'normalizeTrailingSlash' => true,
                'action' => 301,
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
                'links' => ['app\features\links\Shortcode', 'render'],
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
