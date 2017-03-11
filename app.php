<?php

require 'bootstrap.php';

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

$app = new Application();

$app['debug'] = true;
$app['api_version'] = '/v1';

$app->after(function (Request $request,Response $response) {
    $response->headers->set('Content-Type', 'application/json');
});

// Registrando o Twig
$app->register(new Silex\Provider\TwigServiceProvider(), [
    'twig.path' => 'views'
]);

$app->register(new \Silex\Provider\ServiceControllerServiceProvider());
// Registrando o Router Service Provider
$app->register(new ApiMaster\Service\RouterServiceProvider());

// Registrando o Controller Service Provider
$app->register(new ApiMaster\Service\ControllerServiceProvider());

/**
 * Registra o Doctrine ORM Service Provider
 */
$app->register(new \Silex\Provider\DoctrineServiceProvider(), array(
    'dbs.options' => array(
        'default' => $dbParams
    )
));

$app->register(new \Dflydev\Provider\DoctrineOrm\DoctrineOrmServiceProvider(), array(
    'orm.proxies_dir' => '/tmp',
    'orm.em.options' => array(
        'mappings' => array(
            array(
                'type' => 'annotation',
                'use_simple_annotation_reader' => false,
                'namespace' => 'ApiMaster\Model',
                'path' => __DIR__ . '/src'
            ),
        ),
    ),
    'orm.proxies_namespace' => 'EntityProxy',
    'orm.auto_generate_proxies' => true,
    'orm.default_cache' => 'array'
));

$app->register(new \ApiMaster\Service\JWTServiceProvider(), [
    'iss' => $_SERVER['SERVER_NAME'],
    'secret' => 'xyxyoks',
    'expires' => 3600,
    'signer' => 'HMACS'
]);


