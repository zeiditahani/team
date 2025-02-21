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
        '/api/admin/create-task' => [[['_route' => 'admin_create_task', '_controller' => 'App\\Controller\\AdminController::createTask'], null, ['POST' => 0], null, false, false, null]],
        '/api/admin/list-tasks' => [[['_route' => 'admin_list_tasks', '_controller' => 'App\\Controller\\AdminController::listTasks'], null, ['GET' => 0], null, false, false, null]],
        '/api/admin/create-trainingsession' => [[['_route' => 'admin_create_trainingsession', '_controller' => 'App\\Controller\\AdminController::createTrainingsession'], null, ['POST' => 0], null, false, false, null]],
        '/api/admin/list-trainingsessions' => [[['_route' => 'admin_list_trainingsessions', '_controller' => 'App\\Controller\\AdminController::listTrainingsessions'], null, ['GET' => 0], null, false, false, null]],
        '/api/admin/create-medical-cost' => [[['_route' => 'admin_create_medical_cost', '_controller' => 'App\\Controller\\AdminController::createMedicalCost'], null, ['POST' => 0], null, false, false, null]],
        '/api/admin/list-medical-costs' => [[['_route' => 'admin_list_medical_costs', '_controller' => 'App\\Controller\\AdminController::listMedicalCosts'], null, ['GET' => 0], null, false, false, null]],
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
                        .'|med(?'
                            .'|ecin/([^/]++)(*:86)'
                            .'|ical\\-cost/([^/]++)(*:112)'
                        .')'
                        .'|kine/([^/]++)(*:134)'
                        .'|p(?'
                            .'|resident/([^/]++)(*:163)'
                            .'|hotographe/([^/]++)(*:190)'
                        .')'
                        .'|entraineur/([^/]++)(*:218)'
                        .'|joueur/([^/]++)(*:241)'
                        .'|t(?'
                            .'|a(?'
                                .'|lent/([^/]++)(*:270)'
                                .'|sk/([^/]++)(*:289)'
                            .')'
                            .'|rainingsession/([^/]++)(*:321)'
                        .')'
                    .')'
                    .'|desactiver\\-contrat\\-(?'
                        .'|medecin/([^/]++)(*:371)'
                        .'|entraineur/([^/]++)(*:398)'
                        .'|joueur/([^/]++)(*:421)'
                        .'|photographe/([^/]++)(*:449)'
                    .')'
                    .'|renouvellement\\-contrat\\-(?'
                        .'|medecin/([^/]++)(*:502)'
                        .'|joueur/([^/]++)(*:525)'
                        .'|photographe/([^/]++)(*:553)'
                    .')'
                    .'|acheter\\-joueur/([^/]++)(*:586)'
                .')'
            .')/?$}sDu',
    ],
    [ // $dynamicRoutes
        35 => [[['_route' => '_preview_error', '_controller' => 'error_controller::preview', '_format' => 'html'], ['code', '_format'], null, null, false, true, null]],
        86 => [[['_route' => 'admin_update_medecin', '_controller' => 'App\\Controller\\AdminController::updateMedecin'], ['id'], ['PUT' => 0], null, false, true, null]],
        112 => [[['_route' => 'admin_update_medical_cost', '_controller' => 'App\\Controller\\AdminController::updateMedicalCost'], ['id'], ['PUT' => 0], null, false, true, null]],
        134 => [[['_route' => 'admin_update_kine', '_controller' => 'App\\Controller\\AdminController::updateKine'], ['id'], ['PUT' => 0, 'PATCH' => 1], null, false, true, null]],
        163 => [[['_route' => 'admin_update_president', '_controller' => 'App\\Controller\\AdminController::updatePresident'], ['id'], ['PUT' => 0, 'PATCH' => 1], null, false, true, null]],
        190 => [[['_route' => 'admin_update_photographe', '_controller' => 'App\\Controller\\AdminController::updatePhotographe'], ['id'], ['PUT' => 0, 'PATCH' => 1], null, false, true, null]],
        218 => [[['_route' => 'admin_update_entraineur', '_controller' => 'App\\Controller\\AdminController::updateEntraineur'], ['id'], ['PUT' => 0, 'PATCH' => 1], null, false, true, null]],
        241 => [[['_route' => 'admin_update_joueur', '_controller' => 'App\\Controller\\AdminController::updateJoueur'], ['id'], ['PUT' => 0, 'PATCH' => 1], null, false, true, null]],
        270 => [[['_route' => 'admin_update_talent', '_controller' => 'App\\Controller\\AdminController::updateTalent'], ['id'], ['PUT' => 0], null, false, true, null]],
        289 => [[['_route' => 'admin_update_task', '_controller' => 'App\\Controller\\AdminController::updateTask'], ['id'], ['PUT' => 0], null, false, true, null]],
        321 => [[['_route' => 'admin_update_trainingsession', '_controller' => 'App\\Controller\\AdminController::updateTrainingsession'], ['id'], ['PUT' => 0], null, false, true, null]],
        371 => [[['_route' => 'admin_desactiver_contrat_medecin', '_controller' => 'App\\Controller\\AdminController::desactiverContratMedecin'], ['medecin_id'], ['PATCH' => 0], null, false, true, null]],
        398 => [[['_route' => 'admin_desactiver_contrat_entraineur', '_controller' => 'App\\Controller\\AdminController::desactiverContratEntraineur'], ['entraineur_id'], ['PATCH' => 0], null, false, true, null]],
        421 => [[['_route' => 'admin_desactiver_contrat_joueur', '_controller' => 'App\\Controller\\AdminController::desactiverContrat'], ['joueur_id'], ['PATCH' => 0], null, false, true, null]],
        449 => [[['_route' => 'admin_desactiver_contrat_photographe', '_controller' => 'App\\Controller\\AdminController::desactiverContratPhotographe'], ['photographe_id'], ['PATCH' => 0], null, false, true, null]],
        502 => [[['_route' => 'admin_renouvellement_contrat-medecin', '_controller' => 'App\\Controller\\AdminController::createContratMedecin'], ['medecin_id'], ['POST' => 0], null, false, true, null]],
        525 => [[['_route' => 'admin_renouvellement_contrat-joueur', '_controller' => 'App\\Controller\\AdminController::createContratJoueur'], ['joueur_id'], ['POST' => 0], null, false, true, null]],
        553 => [[['_route' => 'admin_renouvellement_contrat_photographe', '_controller' => 'App\\Controller\\AdminController::createContratPhotographe'], ['photographe_id'], ['POST' => 0], null, false, true, null]],
        586 => [
            [['_route' => 'admin_acheter_joueur', '_controller' => 'App\\Controller\\AdminController::acheterJoueur'], ['id'], ['POST' => 0], null, false, true, null],
            [null, null, null, null, false, false, 0],
        ],
    ],
    null, // $checkCondition
];
