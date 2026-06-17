<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('/achat/saisir', 'AchatController::saisirAchat');
$routes->get('/', 'CaisseController::index');
$routes->post('caisse/selectionner', 'CaisseController::selectionner');
$routes->get('achats', 'CaisseController::achats');
