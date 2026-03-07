<?php

// https://www.cyon.ch/support/a/e-mail-konto-einrichten-imap-pop3-und-smtp-einstellungen

return [
    'class' => 'yii\swiftmailer\Mailer',
    'viewPath' => '@app/layouts/mail',
    'htmlLayout' => 'html',
    'textLayout' => 'text',
    'useFileTransport' => false,
    'transport' => [
        'class' => 'Swift_SmtpTransport',
        'host' => ($_ENV['MAILER_HOST'] ?? ''),
        'username' => ($_ENV['MAILER_USERNAME'] ?? ''),
        'password' => ($_ENV['MAILER_PASSWORD'] ?? ''),
        'port' => ($_ENV['MAILER_PORT'] ?? ''),
        'encryption' => ($_ENV['MAILER_ENCRYPTION'] ?? '')
    ]
];
