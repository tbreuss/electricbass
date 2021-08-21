<?php

Yii::setAlias('@tests', dirname(__DIR__) . '/tests/codeception');

$config = [
    'id' => 'basic-console',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'app\commands',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'log' => require(__DIR__ . '/log.php'),
        'db' => require(__DIR__ . '/db.php'),
        'urlManager' => [
            'class' => 'yii\web\UrlManager',
            /* @phpstan-ignore-next-line */
            'hostInfo' => YII_ENV_DEV ? 'http://electricbass.test' : 'https://www.electricbass.ch',
            'scriptUrl' => '',
            'enablePrettyUrl' => true,
            'rules' => require(__DIR__ . '/rules.php')
        ],
        'mailer' => require(__DIR__ . '/mailer.php'),
    ],
    'params' => require(__DIR__ . '/params.php'),
    /*
    'controllerMap' => [
        'fixture' => [ // Fixture generation command line.
            'class' => 'yii\faker\FixtureController',
        ],
    ],
    */
];

/* @phpstan-ignore-next-line */
if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
    ];
}

return $config;
