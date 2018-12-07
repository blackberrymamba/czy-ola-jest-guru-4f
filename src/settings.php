<?php
require __DIR__ . '/helpers.php';

return [
    'settings' => [
        'displayErrorDetails' => false, // set to false in production
        'addContentLengthHeader' => false, // Allow the web server to send the content-length header
        // Renderer settings
        'renderer' => [
            'template_path' => __DIR__ . '/../templates/',
        ],
        // Monolog settings
        'logger' => [
            'name' => 'slim-app',
            'path' => isset($_ENV['docker']) ? 'php://stdout' : __DIR__ . '/../logs/app.log',
            'level' => \Monolog\Logger::DEBUG,
        ],
        'db' => [
            'location' => __DIR__ . '/../database/db.sqlite'
        ],
        'captcha' => [
            'sitekey' => 'xxxxxxxxxxxxxxxxxxxxxxx',
            'secretkey' => 'xxxxxxxxxxxxxxxxxxxxxxx'
        ],
        'site' => [
            'base_url' => base_url(),
            'title' => 'Czy Ola jest guru?',
            'question' => 'Czy Ola jest dzisiaj guru?',
            'description' => 'Na ile procent Ola jest dziś guru? Przedstaw swoją opinię i zobacz codzienną statystykę!',
            'yesbtn' => 'Tak!',
            'nobtn' => 'Zdecydowanie nie.',
            'results_text' => 'Dzisiaj Ola jest guru na ${value}%!',
            'empty_results_text' => 'Dzisiaj jeszcze nie wiadomo jak bardzo Ola jest guru. Bądź pierwszy i oddaj głos!',
        ]
    ],
];
