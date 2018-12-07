<?php

use Geggleto\Service\Captcha;
use ReCaptcha\ReCaptcha;

// Routes
$captcha = $app->getContainer()->get(Captcha::class);
$app->get('/', \App\Controllers\HomeController::class . ':index');
$app->get('/api/v1/GetGuruLevels/[{type}]', \App\Controllers\ApiController::class . ':GetGuruLevels');
$app->get('/api/v1/GetVotes/[{type}]', \App\Controllers\ApiController::class . ':GetVotes');
$app->post('/api/v1/Vote/', \App\Controllers\ApiController::class . ':Vote')->add($captcha);

