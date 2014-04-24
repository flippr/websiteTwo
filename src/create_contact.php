<?php
// create_product.php
require_once "Bootstrap.php";

$newProductName = $argv[1];
$product = new RemindCloud\Entity\MessageTransport;
$product->setName($newProductName);

$entityManager->persist($product);
$entityManager->flush();

echo "Created Contact Type with ID " . $product->getId() . "\n";