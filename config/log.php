<?php

$maskVars = [
    '_SERVER.HTTP_AUTHORIZATION',
    '_SERVER.PHP_AUTH_USER',
    '_SERVER.PHP_AUTH_PW',
    '_SERVER.MYSQL_USER',
    '_SERVER.MYSQL_PASS',
    '_SERVER.AMAZON_PAAPI5_ACCESS_KEY',
    '_SERVER.AMAZON_PAAPI5_SECRET_KEY',
    '_SERVER.ADMIN_USER',
    '_SERVER.ADMIN_PASS',
    '_SERVER.ENCRYPTION_KEY',
    '_SERVER.COOKIE_VALIDATION_KEY',
    '_SERVER.TWITTER_CONSUMER_KEY',
    '_SERVER.TWITTER_CONSUMER_SECRET',
    '_SERVER.TWITTER_USER_TOKEN',
    '_SERVER.TWITTER_USER_SECRET',
    '_SERVER.MAILER_USERNAME',
    '_SERVER.MAILER_PASSWORD',
];

/* @phpstan-ignore-next-line */
if (YII_ENV_DEV) {
    return [
        'traceLevel' => 3,
        'targets' => [
            [
                'class' => 'yii\log\FileTarget',
                'levels' => ['error', 'warning'],
                'maskVars' => $maskVars
            ],
        ],
    ];
}

return [
    'traceLevel' => 0,
    'targets' => [
        [
            'class' => 'yii\log\DbTarget',
            'levels' => ['error', 'warning'],
            'maskVars' => $maskVars,
            'except' => [
                'yii\web\HttpException:404',
            ],
        ],
        [
            'class' => 'yii\log\EmailTarget',
            'mailer' => 'mailer',
            'levels' => ['error', 'warning'],
            'maskVars' => $maskVars,
            'except' => [
                'yii\web\HttpException:404',
            ],
            'message' => [
                'from' => ['noreply@electricbass.ch'],
                'to' => [($_ENV['LOG_EMAIL_TARGET_TO'] ?? '')],
                'subject' => 'Log message | electricbass.ch',
            ],
        ],
    ],
];
