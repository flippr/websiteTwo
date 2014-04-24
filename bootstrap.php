<?php
// bootstrap.php
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;

require_once "vendor/autoload.php";

// Create a simple "default" Doctrine ORM configuration for Annotations
$isDevMode = true;
$config = Setup::createAnnotationMetadataConfiguration(array(__DIR__ . "/lib/RemindCloud/Entity"), $isDevMode);
// or if you prefer yaml or XML
//$config = Setup::createXMLMetadataConfiguration(array(__DIR__."/config/xml"), $isDevMode);
//$config = Setup::createYAMLMetadataConfiguration(array(__DIR__."/config/yaml"), $isDevMode);

// database configuration parameters
$dbParams = array(
    'driver' => 'pdo_mysql',
    'host' => '198.211.102.146',
    'user' => 'jstevens',
    'password' => 'mmgood85',
    'dbname' => 'rmcloud',
);

// obtaining the entity manager
$em = EntityManager::create($dbParams, $config);
// generate the Doctrine HelperSet
$helperSet = new \Symfony\Component\Console\Helper\HelperSet(array(
    'db' => new \Doctrine\DBAL\Tools\Console\Helper\ConnectionHelper($em->getConnection()),
    'em' => new \Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper($em)
));

\Doctrine\ORM\Tools\Console\ConsoleRunner::run($helperSet);
