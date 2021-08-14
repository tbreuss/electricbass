<?php

if (YII_ENV_DEV) {
    return [
        'traceLevel' => 3,
        'targets' => [
            [
                'class' => 'yii\log\FileTarget',
                'levels' => ['error', 'warning'],
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
            'logVars' => ['_GET', '_POST', '_FILES', '_COOKIE', '_SESSION', '_SERVER'],
            'except' => [
                'yii\web\HttpException:404',
            ],
        ],
        [
            'class' => 'yii\log\EmailTarget',
            'mailer' => 'mailer',
            'levels' => ['error', 'warning'],
            'logVars' => ['_GET', '_POST', '_FILES', '_COOKIE', '_SESSION', '_SERVER'],
            'except' => [
                'yii\web\HttpException:404',
            ],
            'message' => [
                'from' => ['noreply@electricbass.ch'],
                'to' => [$_ENV['LOG_EMAIL_TARGET_TO']],
                'subject' => 'Log message | electricbass.ch',
            ],
        ],
    ],
];
