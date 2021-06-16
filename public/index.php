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


/**
 * Error and Exception handling
 */
error_reporting(E_ALL);
set_error_handler('Core\Error::errorHandler');
set_exception_handler('Core\Error::exceptionHandler');

session_start();

/**
 * Routing
 */
$router = new Core\Router();

// Add the routes
$router->add('', ['controller' => 'Home', 'action' => 'index']);
$router->add('menu', ['controller' => 'Menu', 'action' => 'menu']);
$router->add('expense', ['controller' => 'Expense', 'action' => 'expense']);
$router->add('income', ['controller' => 'Income', 'action' => 'income']);
$router->add('balance', ['controller' => 'Balance', 'action' => 'balance']);
$router->add('balanceSum', ['controller' => 'Balance', 'action' => 'create']);
$router->add('logout', ['controller' => 'Logout', 'action' => 'logout']);
$router->add('settings', ['controller' => 'Settings', 'action' => 'settings']);
$router->add('userData', ['controller' => 'UserData', 'action' => 'userData']);
$router->add('{controller}/{action}');
    
$router->dispatch($_SERVER['QUERY_STRING']);
