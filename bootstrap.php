<?php

require 'vendor/autoload.php';

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
use Doctrine\ORM\Tools\Setup;

// Configurando Doctrine

$isDevMode = false;

$paths = array(__DIR__ . '/src/Model');

$dbParams = array(
    'driver'   => 'pdo_mysql',
    'user'     => 'root',
    'password' => '1234',
    'dbname'   => 'api_master',
);

$config = Setup::createConfiguration($isDevMode);

$driver = new AnnotationDriver(new AnnotationReader(), $paths);

$config->setMetadataDriverImpl($driver);

AnnotationRegistry::registerFile(
    __DIR__ . '/vendor/doctrine/orm/lib/Doctrine/ORM/Mapping/Driver/DoctrineAnnotations.php'
);

$entityManager = EntityManager::create($dbParams, $config);
