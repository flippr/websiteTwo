<?php
/**
 * Created by PhpStorm.
 * User: jstevens
 * Date: 4/15/14
 * Time: 9:23 AM
 */
// bootstrap.php
require_once "../vendor/autoload.php";

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;

$paths = array("/path/to/entity-files");
$isDevMode = false;

// the connection configuration
$dbParams = array(
    'driver' => 'pdo_mysql',
    'user' => 'root',
    'password' => '',
    'dbname' => 'foo',
);

$config = Setup::createAnnotationMetadataConfiguration($paths, $isDevMode);
$entityManager = EntityManager::create($dbParams, $config);