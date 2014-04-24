<?php
// list_products.php
require_once "Bootstrap.php";

$productRepository = $entityManager->getRepository('Contact\Type');
$products = $productRepository->findAll();

foreach ($products as $product)
{
    echo sprintf("-%s\n", $product->getName());
}