<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'CaisseController::index');
$routes->post('caisse/selectionner', 'CaisseController::selectionner');
$routes->get('achats', 'CaisseController::achats');
