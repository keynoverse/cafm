<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\Core\Application;
use App\Core\Router;
use App\Core\Database;
use Dotenv\Dotenv;

// Load environment variables
$dotenv = Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

// Initialize application
$app = new Application();

// Initialize database connection
$db = new Database();
$db->connect();

// Initialize router
$router = new Router();

// Define routes
require_once __DIR__ . '/../config/routes.php';

// Run the application
$app->run(); 