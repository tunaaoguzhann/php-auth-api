<?php

require __DIR__ . '/vendor/autoload.php';

use Tunaaoguzhann\PhpAuthApi\Router\Router;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

$router = new Router();
$router->run(); 