<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'User::index', ['filter' => 'role:user']);
$routes->get('/', 'User::index', ['filter' => 'role:admin']);
$routes->get('/admin', 'Admin::index', ['filter' => 'role:admin']);
$routes->get('/admin/ruangan', 'Admin::daftar_ruangan', ['filter' => 'role:admin']);
$routes->get('/admin/(:num)', 'Admin::detail_user/$1', ['filter' => 'role:admin']);
$routes->get('/login', 'Home::login');
$routes->get('/register', 'Home::register');
$routes->get('/user', 'User::index', ['filter' => 'role:user']);
$routes->get('/user', 'User::index', ['filter' => 'role:admin']);
$routes->get('/user/rencana', 'User::rencana', ['filter' => 'role:user']);
$routes->get('/user/rencana', 'User::rencana', ['filter' => 'role:admin']);
$routes->get('/user/sewa', 'User::sewa', ['filter' => 'role:user']);
$routes->get('/user/sewa', 'User::sewa', ['filter' => 'role:admin']);
$routes->get('/user/sewa/getKetersediaanRuangan/(:segment)', 'User::getKetersediaanRuangan/$1', ['filter' => 'role:user']);
$routes->get('/user/sewa/getKetersediaanRuangan/(:segment)', 'User::getKetersediaanRuangan/$1', ['filter' => 'role:admin']);
$routes->get('/user/sewa/getNominalPerJam/(:segment)', 'User::getNominalPerJam/$1', ['filter' => 'role:user']);
$routes->get('/user/sewa/getNominalPerJam/(:segment)', 'User::getNominalPerJam/$1', ['filter' => 'role:admin']);
$routes->post('/user/simpanTransaksi', 'User::simpanTransaksi', ['filter' => 'role:user']);
$routes->post('/user/simpanTransaksi', 'User::simpanTransaksi', ['filter' => 'role:admin']);
$routes->get('/user/detailTransaksi/(:num)', 'User::detailTransaksi/$1', ['filter' => 'role:user']);
$routes->get('/user/detailTransaksi/(:num)', 'User::detailTransaksi/$1', ['filter' => 'role:admin']);
