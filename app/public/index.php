<?php

declare(strict_types=1);

require_once(__DIR__ . '/../vendor/autoload.php');

use App\Controllers\TaskController;
use App\Database;
use App\Gateways\TaskGateway;

set_exception_handler('\App\ErrorHandler::handleException');

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$parts = explode('/', $path);

$resource = $parts[2];
$id = $parts[3] ?? null;

if ($resource != 'tasks') {
    http_response_code(404);
    die();
}


header('Content-type: application/json; charset=UTF-8');
$database = new Database($_ENV['DB_HOST'], $_ENV['DB_USER'], $_ENV['DB_PASSWORD'], $_ENV['DB_DATABASE']);
$task_gateway = new TaskGateway($database);
$controller = new TaskController($task_gateway);
$controller->processRequest($_SERVER['REQUEST_METHOD'], $id);