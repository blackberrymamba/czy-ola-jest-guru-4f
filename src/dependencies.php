<?php

// DIC configuration

use Geggleto\Service\Captcha;
use ReCaptcha\ReCaptcha;

$container = $app->getContainer();

// view renderer
$container['renderer'] = function ($c) {
    $settings = $c->get('settings')['renderer'];
    return new Slim\Views\PhpRenderer($settings['template_path']);
};

// monolog
$container['logger'] = function ($c) {
    $settings = $c->get('settings')['logger'];
    $logger = new Monolog\Logger($settings['name']);
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['path'], $settings['level']));
    return $logger;
};

// databse 
$container['db'] = function ($c) {
    $dbo = new PDO("sqlite:" . $c->get('settings')['db']['location']);
    return $dbo;
};

$container[Recaptcha::class] = function($c) {
    $secret = $c->get('settings')['captcha']['secretkey'];
    return new \ReCaptcha\ReCaptcha($secret);
};
$container[Captcha::class] = function ($c) {
    return new Captcha($c[ReCaptcha::class]);
};

$container['errorHandler'] = function ($c) {
    return function ($request, $response, $exception) use ($c) {
        return $response
                        ->withJson(array(
                            "error" => array(
                                "text" => $exception->getMessage()
                            )
                                ), 400);
    };
};
