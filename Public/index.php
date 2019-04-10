<?php

/**
 * Front controller
 *
 * PHP version 7.0
 */

/**
 * Composer
 */
require dirname(__DIR__) . '/vendor/autoload.php';
use Core\Router;

session_start();


/**
 * Error and Exception handling
 */
error_reporting(E_ALL);
set_error_handler('Core\Error::errorHandler');
set_exception_handler('Core\Error::exceptionHandler');


/**
 * Routing
 */
$router = new Router();

// Add the routes
$router->add('', ['controller' => 'HomeController', 'action' => 'login']);
$router->add('register', ['controller' => 'HomeController', 'action' => 'register']);
$router->add('logout', ['controller' => 'HomeController', 'action' => 'logout']);
$router->add('users', ['controller' => 'HomeController', 'action' => 'users']);

$router->add('chat', ['controller' => 'MessageController', 'action' => 'index']);
$router->add('send_message', ['controller' => 'MessageController', 'action' => 'sendMessage']);
$router->add('conversation_with', ['controller' => 'MessageController', 'action' => 'getConversation']);

$router->dispatch($_SERVER['QUERY_STRING']);
