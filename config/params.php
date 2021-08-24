<?php

return [
    'adminEmail' => ($_ENV['ADMIN_EMAIL'] ?? ''),
    'senderEmail' => [
        ($_ENV['SENDER_EMAIL'] ?? '') => 'ELECTRICBASS'
    ],
    'encryptionKey' => ($_ENV['ENCRYPTION_KEY'] ?? '')
];
