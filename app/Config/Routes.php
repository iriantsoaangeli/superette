<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'CaisseController::index');
$routes->post('caisse/selectionner', 'CaisseController::selectionner');
$routes->get('achats', 'AchatController::achats');
$routes->get('achat/saisir', 'AchatController::saisirAchat');
$routes->post('achat/enregistrer', 'AchatController::enregistrerAchat');
