<?php

// https://www.cyon.ch/support/a/e-mail-konto-einrichten-imap-pop3-und-smtp-einstellungen

return [
    'class' => 'yii\swiftmailer\Mailer',
    // send all mails to a file by default. You have to set
    // 'useFileTransport' to false and configure a transport
    // for the mailer to send real emails.
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
