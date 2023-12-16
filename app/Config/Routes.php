<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');


$routes->post('/login', 'Auth::login');



$routes->post('/create-user', 'Usercontroller::store');

$routes->get('/get-users', 'Usercontroller::getUsers');

$routes->put('/update-user/(:num)', 'Usercontroller::update/$1');

$routes->delete('/delete-user/(:num)', 'Usercontroller::delete/$1');




$routes->post('/task-create', 'TaskController::store');

$routes->put('/update-task/(:num)', 'TaskController::update/$1');

$routes->delete('/delete-task/(:num)', 'TaskController::delete/$1');

$routes->get('/list-tasks', 'TaskController::listTasks');

