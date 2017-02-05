<?php
chdir(dirname(__DIR__));

require 'vendor/autoload.php';

use Silex\Application;

$app = new Application();

$app['debug'] = true;

// Registrando o Twig
$app->register(new Silex\Provider\TwigServiceProvider(), [
    'twig.path' => 'views'
]);

$app->register(new \Silex\Provider\ServiceControllerServiceProvider());
// Registrando o Router Service Provider
$app->register(new ApiMaster\Service\RouterServiceProvider());

// Registrando o Controller Service Provider
$app->register(new ApiMaster\Service\ControllerServiceProvider());

$app->run();
