<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');


$routes->post('/login', 'Auth::login');

$routes->get('/welcome', 'Usercontroller::index');

$routes->post('/create-user', 'Usercontroller::store');

$routes->get('/get-users', 'Usercontroller::getUsers');

$routes->put('/update-user/(:num)', 'Usercontroller::updateUser/$1');

$routes->delete('/delete-user/(:num)', 'Usercontroller::deleteUser/$1');

$routes->get('/users-list', 'Usercontroller::generatePdfUsers');

$routes->post('/upload-image', 'Usercontroller::uploadImage');