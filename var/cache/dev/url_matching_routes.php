<?php

/**
 * This file has been auto-generated
 * by the Symfony Routing Component.
 */

return [
    false, // $matchHost
    [ // $staticRoutes
        '/api/admin/create-medecin' => [[['_route' => 'admin_create_medecin', '_controller' => 'App\\Controller\\AdminController::createMedecin'], null, ['POST' => 0], null, false, false, null]],
        '/api/admin/listmedecins' => [[['_route' => 'admin_listmedecins', '_controller' => 'App\\Controller\\AdminController::listMedecins'], null, ['GET' => 0], null, false, false, null]],
        '/api/admin/create-kine' => [[['_route' => 'admin_create_kine', '_controller' => 'App\\Controller\\AdminController::createKine'], null, ['POST' => 0], null, false, false, null]],
        '/api/admin/listkines' => [[['_route' => 'admin_get_kines', '_controller' => 'App\\Controller\\AdminController::getKines'], null, ['GET' => 0], null, false, false, null]],
        '/api/admin/create-president' => [[['_route' => 'admin_create_president', '_controller' => 'App\\Controller\\AdminController::createPresident'], null, ['POST' => 0], null, false, false, null]],
        '/api/admin/listpresidents' => [[['_route' => 'admin_listpresidents', '_controller' => 'App\\Controller\\AdminController::listPresidents'], null, ['GET' => 0], null, false, false, null]],
        '/api/admin/create-entraineur' => [[['_route' => 'admin_create_entraineur', '_controller' => 'App\\Controller\\AdminController::createEntraineur'], null, ['POST' => 0], null, false, false, null]],
        '/api/admin/listentraineurs' => [[['_route' => 'admin_listentraineurs', '_controller' => 'App\\Controller\\AdminController::listEntraineurs'], null, ['GET' => 0], null, false, false, null]],
        '/api/admin/create-joueur' => [[['_route' => 'admin_create_joueur', '_controller' => 'App\\Controller\\AdminController::createJoueur'], null, ['POST' => 0], null, false, false, null]],
        '/api/admin/listjoueurs' => [[['_route' => 'admin_listjoueurs', '_controller' => 'App\\Controller\\AdminController::listJoueurs'], null, ['GET' => 0], null, false, false, null]],
        '/api/admin/create-photographe' => [[['_route' => 'admin_create_photographe', '_controller' => 'App\\Controller\\AdminController::createPhotographe'], null, ['POST' => 0], null, false, false, null]],
        '/api/admin/listphotographes' => [[['_route' => 'admin_listphotographes', '_controller' => 'App\\Controller\\AdminController::listPhotographes'], null, ['GET' => 0], null, false, false, null]],
        '/api/admin/create-talent' => [[['_route' => 'admin_create_talent', '_controller' => 'App\\Controller\\AdminController::createTalent'], null, ['POST' => 0], null, false, false, null]],
        '/api/admin/listtalents' => [[['_route' => 'admin_get_talents', '_controller' => 'App\\Controller\\AdminController::getTalents'], null, ['GET' => 0], null, false, false, null]],
        '/api/dashboard' => [[['_route' => 'api_app_dashboard', '_controller' => 'App\\Controller\\DashboardController::index'], null, null, null, false, false, null]],
        '/api/equipe/create-equipe' => [[['_route' => 'equipe_create_equipe', '_controller' => 'App\\Controller\\EquipeController::createEquipe'], null, ['POST' => 0], null, false, false, null]],
        '/api/equipe/informations' => [[['_route' => 'equipe_get_equipe', '_controller' => 'App\\Controller\\EquipeController::getEquipe'], null, ['GET' => 0], null, false, false, null]],
        '/api/equipe/update-president' => [[['_route' => 'equipe_update_president', '_controller' => 'App\\Controller\\EquipeController::updatePresident'], null, ['PUT' => 0], null, false, false, null]],
        '/api/register' => [[['_route' => 'api_register', '_controller' => 'App\\Controller\\RegistrationController::index'], null, ['POST' => 0], null, false, false, null]],
        '/api/login_check' => [[['_route' => 'api_login_check'], null, null, null, false, false, null]],
    ],
    [ // $regexpList
        0 => '{^(?'
                .'|/_error/(\\d+)(?:\\.([^/]++))?(*:35)'
                .'|/api/admin/(?'
                    .'|update\\-(?'
                        .'|medecin/([^/]++)(*:83)'
                        .'|kine/([^/]++)(*:103)'
                        .'|p(?'
                            .'|resident/([^/]++)(*:132)'
                            .'|hotographe/([^/]++)(*:159)'
                        .')'
                        .'|entraineur/([^/]++)(*:187)'
                        .'|joueur/([^/]++)(*:210)'
                        .'|talent/([^/]++)(*:233)'
                    .')'
                    .'|desactiver\\-contrat\\-(?'
                        .'|medecin/([^/]++)(*:282)'
                        .'|entraineur/([^/]++)(*:309)'
                        .'|joueur/([^/]++)(*:332)'
                        .'|photographe/([^/]++)(*:360)'
                    .')'
                    .'|renouvellement\\-contrat\\-(?'
                        .'|medecin/([^/]++)(*:413)'
                        .'|entraineur/([^/]++)(*:440)'
                        .'|joueur/([^/]++)(*:463)'
                        .'|photographe/([^/]++)(*:491)'
                    .')'
                    .'|acheter\\-joueur/([^/]++)(*:524)'
                .')'
            .')/?$}sDu',
    ],
    [ // $dynamicRoutes
        35 => [[['_route' => '_preview_error', '_controller' => 'error_controller::preview', '_format' => 'html'], ['code', '_format'], null, null, false, true, null]],
        83 => [[['_route' => 'admin_update_medecin', '_controller' => 'App\\Controller\\AdminController::updateMedecin'], ['id'], ['PUT' => 0], null, false, true, null]],
        103 => [[['_route' => 'admin_update_kine', '_controller' => 'App\\Controller\\AdminController::updateKine'], ['id'], ['PUT' => 0, 'PATCH' => 1], null, false, true, null]],
        132 => [[['_route' => 'admin_update_president', '_controller' => 'App\\Controller\\AdminController::updatePresident'], ['id'], ['PUT' => 0, 'PATCH' => 1], null, false, true, null]],
        159 => [[['_route' => 'admin_update_photographe', '_controller' => 'App\\Controller\\AdminController::updatePhotographe'], ['id'], ['PUT' => 0, 'PATCH' => 1], null, false, true, null]],
        187 => [[['_route' => 'admin_update_entraineur', '_controller' => 'App\\Controller\\AdminController::updateEntraineur'], ['id'], ['PUT' => 0, 'PATCH' => 1], null, false, true, null]],
        210 => [[['_route' => 'admin_update_joueur', '_controller' => 'App\\Controller\\AdminController::updateJoueur'], ['id'], ['PUT' => 0, 'PATCH' => 1], null, false, true, null]],
        233 => [[['_route' => 'admin_update_talent', '_controller' => 'App\\Controller\\AdminController::updateTalent'], ['id'], ['PUT' => 0], null, false, true, null]],
        282 => [[['_route' => 'admin_desactiver_contrat_medecin', '_controller' => 'App\\Controller\\AdminController::desactiverContratMedecin'], ['medecin_id'], ['PATCH' => 0], null, false, true, null]],
        309 => [[['_route' => 'admin_desactiver_contrat_entraineur', '_controller' => 'App\\Controller\\AdminController::desactiverContratEntraineur'], ['entraineur_id'], ['PATCH' => 0], null, false, true, null]],
        332 => [[['_route' => 'admin_desactiver_contrat_joueur', '_controller' => 'App\\Controller\\AdminController::desactiverContrat'], ['joueur_id'], ['PATCH' => 0], null, false, true, null]],
        360 => [[['_route' => 'admin_desactiver_contrat_photographe', '_controller' => 'App\\Controller\\AdminController::desactiverContratPhotographe'], ['photographe_id'], ['PATCH' => 0], null, false, true, null]],
        413 => [[['_route' => 'admin_renouvellement_contrat-medecin', '_controller' => 'App\\Controller\\AdminController::createContratMedecin'], ['medecin_id'], ['POST' => 0], null, false, true, null]],
        440 => [[['_route' => 'admin_renouvellement_contrat_entraineur', '_controller' => 'App\\Controller\\AdminController::createContratEntraineur'], ['entraineur_id'], ['POST' => 0], null, false, true, null]],
        463 => [[['_route' => 'admin_renouvellement_contrat-joueur', '_controller' => 'App\\Controller\\AdminController::createContratJoueur'], ['joueur_id'], ['POST' => 0], null, false, true, null]],
        491 => [[['_route' => 'admin_renouvellement_contrat_photographe', '_controller' => 'App\\Controller\\AdminController::createContratPhotographe'], ['photographe_id'], ['POST' => 0], null, false, true, null]],
        524 => [
            [['_route' => 'admin_acheter_joueur', '_controller' => 'App\\Controller\\AdminController::acheterJoueur'], ['id'], ['POST' => 0], null, false, true, null],
            [null, null, null, null, false, false, 0],
        ],
    ],
    null, // $checkCondition
];
