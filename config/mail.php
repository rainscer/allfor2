<?php

return [

    'driver' => env('MAIL_DRIVER', 'smtp'),

    'host' => env('MAIL_HOST', 'smtp.korovo.com'),

    'port' => env('MAIL_PORT', 587),

    'from' => [
        'address' => env('MAIL_FROM_ADDRESS', 'cs@korovo.com'),
        'name' => env('MAIL_FROM_NAME', 'Allfor2'),
    ],

    'adminEmail' => env('ADMIN_EMAIL', 'info@agratravel.nl'),

    'encryption' => env('MAIL_ENCRYPTION', 'tls'),

    'username' => env('MAIL_USERNAME'),

    'password' => env('MAIL_PASSWORD'),

	'sendmail' => '/usr/sbin/sendmail -bs',

	'pretend' => false,

	'admin_order' => env('MAIL_FROM_ADDRESS', 'cs@korovo.com'),

	'admin_question' => env('MAIL_FROM_ADDRESS', 'cs@korovo.com'),
];