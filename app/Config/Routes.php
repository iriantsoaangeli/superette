<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Auth
$routes->get('/',              'AuthController::login');
$routes->post('connexion',     'AuthController::connecter');
$routes->get('deconnexion',    'AuthController::deconnecter');

// Accueil / Caisse
$routes->get('accueil',                'CaisseController::index');
$routes->post('caisse/selectionner',   'CaisseController::selectionner');

// Achats
$routes->get('achats',             'AchatController::achats');
$routes->get('achat/saisir',       'AchatController::saisirAchat');
$routes->post('achat/enregistrer', 'AchatController::enregistrerAchat');
$routes->post('achat/cloturer',    'AchatController::cloturerAchat');
