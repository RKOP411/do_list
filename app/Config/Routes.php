<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Tasks');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(false);  // Change to false for better control

// Main routes
$routes->get('/', 'Tasks::index');
$routes->get('/home', 'Home::index');

//Action
$routes->post('/tasks/store', 'Tasks::store');  
$routes->post('/tasks/delete/(:num)', 'Tasks::delete/$1');
$routes->get('/tasks', 'Tasks::index');
