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
        '/api/admin/training-sessions' => [[['_route' => 'admin_list_training_sessions', '_controller' => 'App\\Controller\\AdminController::listTrainingSessions'], null, ['GET' => 0], null, false, false, null]],
        '/api/admin/create-medical-cost' => [[['_route' => 'admin_create_medical_cost', '_controller' => 'App\\Controller\\AdminController::createMedicalCost'], null, ['POST' => 0], null, false, false, null]],
        '/api/admin/list-medical-costs' => [[['_route' => 'admin_list_medical_costs', '_controller' => 'App\\Controller\\AdminController::listMedicalCosts'], null, ['GET' => 0], null, false, false, null]],
        '/api/dashboard' => [[['_route' => 'api_app_dashboard', '_controller' => 'App\\Controller\\DashboardController::index'], null, null, null, false, false, null]],
        '/api/equipe/create-equipe' => [[['_route' => 'equipe_create_equipe', '_controller' => 'App\\Controller\\EquipeController::createEquipe'], null, ['POST' => 0], null, false, false, null]],
        '/api/equipe/informations' => [[['_route' => 'equipe_get_equipe', '_controller' => 'App\\Controller\\EquipeController::getEquipe'], null, ['GET' => 0], null, false, false, null]],
        '/api/equipe/update-president' => [[['_route' => 'equipe_update_president', '_controller' => 'App\\Controller\\EquipeController::updatePresident'], null, ['PUT' => 0], null, false, false, null]],
        '/api/fan/create-fan' => [[['_route' => 'fan_app_fan_createfan', '_controller' => 'App\\Controller\\FanController::createFan'], null, ['POST' => 0], null, false, false, null]],
        '/api/fan/listfans' => [[['_route' => 'fan_app_fan_getallfans', '_controller' => 'App\\Controller\\FanController::getAllFans'], null, ['GET' => 0], null, false, false, null]],
        '/api/fan/create-fanrevenue' => [[['_route' => 'fan_app_fan_createfanrevenue', '_controller' => 'App\\Controller\\FanController::createFanRevenue'], null, ['POST' => 0], null, false, false, null]],
        '/api/fan/listfanrevenues' => [[['_route' => 'fan_app_fan_getallfanrevenues', '_controller' => 'App\\Controller\\FanController::getAllFanRevenues'], null, ['GET' => 0], null, false, false, null]],
        '/api/match/create' => [[['_route' => 'match_create_match', '_controller' => 'App\\Controller\\MatchController::createMatch'], null, ['POST' => 0], null, false, false, null]],
        '/api/match/equipe-adversaire/list' => [[['_route' => 'match_get_equipes_adverses_list', '_controller' => 'App\\Controller\\MatchController::getEquipesAdversesList'], null, ['GET' => 0], null, false, false, null]],
        '/api/match/list' => [[['_route' => 'match_list_matches', '_controller' => 'App\\Controller\\MatchController::listMatches'], null, ['GET' => 0], null, false, false, null]],
        '/api/register' => [[['_route' => 'api_register', '_controller' => 'App\\Controller\\RegistrationController::index'], null, ['POST' => 0], null, false, false, null]],
        '/api/sponsor/create-sponsor' => [[['_route' => 'sponsor_create_sponsor', '_controller' => 'App\\Controller\\SponsorController::createSponsor'], null, ['POST' => 0], null, false, false, null]],
        '/api/sponsor/list-sponsors' => [[['_route' => 'sponsor_list_sponsors', '_controller' => 'App\\Controller\\SponsorController::listSponsors'], null, ['GET' => 0], null, false, false, null]],
        '/api/login_check' => [[['_route' => 'api_login_check'], null, null, null, false, false, null]],
    ],
    [ // $regexpList
        0 => '{^(?'
                .'|/_error/(\\d+)(?:\\.([^/]++))?(*:35)'
                .'|/api/(?'
                    .'|admin/(?'
                        .'|update\\-(?'
                            .'|med(?'
                                .'|ecin/([^/]++)(*:89)'
                                .'|ical\\-cost/([^/]++)(*:115)'
                            .')'
                            .'|kine/([^/]++)(*:137)'
                            .'|p(?'
                                .'|resident/([^/]++)(*:166)'
                                .'|hotographe/([^/]++)(*:193)'
                            .')'
                            .'|entraineur/([^/]++)(*:221)'
                            .'|joueur/([^/]++)(*:244)'
                            .'|t(?'
                                .'|a(?'
                                    .'|lent/([^/]++)(*:273)'
                                    .'|sk/([^/]++)(*:292)'
                                .')'
                                .'|rainingsession/([^/]++)(*:324)'
                            .')'
                        .')'
                        .'|de(?'
                            .'|sactiver\\-contrat\\-(?'
                                .'|medecin/([^/]++)(*:377)'
                                .'|entraineur/([^/]++)(*:404)'
                                .'|joueur/([^/]++)(*:427)'
                                .'|photographe/([^/]++)(*:455)'
                            .')'
                            .'|lete\\-(?'
                                .'|t(?'
                                    .'|ask/([^/]++)(*:489)'
                                    .'|rainingsession/([^/]++)(*:520)'
                                .')'
                                .'|medical\\-cost/([^/]++)(*:551)'
                            .')'
                        .')'
                        .'|renouvellement\\-contrat\\-(?'
                            .'|medecin/([^/]++)(*:605)'
                            .'|entraineur/([^/]++)(*:632)'
                            .'|joueur/([^/]++)(*:655)'
                            .'|photographe/([^/]++)(*:683)'
                        .')'
                        .'|acheter\\-joueur/([^/]++)(*:716)'
                        .'|medical\\-costs/joueur/([^/]++)(*:754)'
                    .')'
                    .'|fan/(?'
                        .'|listfans/([^/]++)(*:787)'
                        .'|update\\-fan(?'
                            .'|/([^/]++)(*:818)'
                            .'|revenue/([^/]++)(*:842)'
                        .')'
                        .'|delete\\-fan(?'
                            .'|/([^/]++)(*:874)'
                            .'|revenue/([^/]++)(*:898)'
                        .')'
                        .'|fan(?'
                            .'|revenues/([^/]++)(*:930)'
                            .'|s/([^/]++)/listrevenues(*:961)'
                        .')'
                    .')'
                    .'|match/(?'
                        .'|([^/]++)/joueurs(*:996)'
                        .'|ajout\\-logistic/([^/]++)(*:1028)'
                        .'|logistic/([^/]++)(*:1054)'
                        .'|([^/]++)(*:1071)'
                        .'|delete/([^/]++)(*:1095)'
                        .'|achat\\-ticket/([^/]++)(*:1126)'
                    .')'
                    .'|sponsor/(?'
                        .'|add\\-revenue/([^/]++)(*:1168)'
                        .'|sponsor/revenues/([^/]++)(*:1202)'
                    .')'
                .')'
            .')/?$}sDu',
    ],
    [ // $dynamicRoutes
        35 => [[['_route' => '_preview_error', '_controller' => 'error_controller::preview', '_format' => 'html'], ['code', '_format'], null, null, false, true, null]],
        89 => [[['_route' => 'admin_update_medecin', '_controller' => 'App\\Controller\\AdminController::updateMedecin'], ['id'], ['PUT' => 0], null, false, true, null]],
        115 => [[['_route' => 'admin_update_medical_cost', '_controller' => 'App\\Controller\\AdminController::updateMedicalCost'], ['id'], ['PUT' => 0], null, false, true, null]],
        137 => [[['_route' => 'admin_update_kine', '_controller' => 'App\\Controller\\AdminController::updateKine'], ['id'], ['PUT' => 0, 'PATCH' => 1], null, false, true, null]],
        166 => [[['_route' => 'admin_update_president', '_controller' => 'App\\Controller\\AdminController::updatePresident'], ['id'], ['PUT' => 0, 'PATCH' => 1], null, false, true, null]],
        193 => [[['_route' => 'admin_update_photographe', '_controller' => 'App\\Controller\\AdminController::updatePhotographe'], ['id'], ['PUT' => 0, 'PATCH' => 1], null, false, true, null]],
        221 => [[['_route' => 'admin_update_entraineur', '_controller' => 'App\\Controller\\AdminController::updateEntraineur'], ['id'], ['PUT' => 0, 'PATCH' => 1], null, false, true, null]],
        244 => [[['_route' => 'admin_update_joueur', '_controller' => 'App\\Controller\\AdminController::updateJoueur'], ['id'], ['PUT' => 0, 'PATCH' => 1], null, false, true, null]],
        273 => [[['_route' => 'admin_update_talent', '_controller' => 'App\\Controller\\AdminController::updateTalent'], ['id'], ['PUT' => 0], null, false, true, null]],
        292 => [[['_route' => 'admin_update_task', '_controller' => 'App\\Controller\\AdminController::updateTask'], ['id'], ['PUT' => 0], null, false, true, null]],
        324 => [[['_route' => 'admin_update_trainingsession', '_controller' => 'App\\Controller\\AdminController::updateTrainingSession'], ['id'], ['PUT' => 0], null, false, true, null]],
        377 => [[['_route' => 'admin_desactiver_contrat_medecin', '_controller' => 'App\\Controller\\AdminController::desactiverContratMedecin'], ['medecin_id'], ['PATCH' => 0], null, false, true, null]],
        404 => [[['_route' => 'admin_desactiver_contrat_entraineur', '_controller' => 'App\\Controller\\AdminController::desactiverContratEntraineur'], ['entraineur_id'], ['PATCH' => 0], null, false, true, null]],
        427 => [[['_route' => 'admin_desactiver_contrat_joueur', '_controller' => 'App\\Controller\\AdminController::desactiverContrat'], ['joueur_id'], ['PATCH' => 0], null, false, true, null]],
        455 => [[['_route' => 'admin_desactiver_contrat_photographe', '_controller' => 'App\\Controller\\AdminController::desactiverContratPhotographe'], ['photographe_id'], ['PATCH' => 0], null, false, true, null]],
        489 => [[['_route' => 'admin_delete_task', '_controller' => 'App\\Controller\\AdminController::deleteTask'], ['id'], ['DELETE' => 0], null, false, true, null]],
        520 => [[['_route' => 'admin_delete_training_session', '_controller' => 'App\\Controller\\AdminController::deleteTrainingSession'], ['id'], ['DELETE' => 0], null, false, true, null]],
        551 => [[['_route' => 'admin_delete_medical_cost', '_controller' => 'App\\Controller\\AdminController::deleteMedicalCost'], ['id'], ['DELETE' => 0], null, false, true, null]],
        605 => [[['_route' => 'admin_renouvellement_contrat-medecin', '_controller' => 'App\\Controller\\AdminController::createContratMedecin'], ['medecin_id'], ['POST' => 0], null, false, true, null]],
        632 => [[['_route' => 'admin_renouvellement_contrat_entraineur', '_controller' => 'App\\Controller\\AdminController::createContratEntraineur'], ['entraineur_id'], ['POST' => 0], null, false, true, null]],
        655 => [[['_route' => 'admin_renouvellement_contrat-joueur', '_controller' => 'App\\Controller\\AdminController::createContratJoueur'], ['joueur_id'], ['POST' => 0], null, false, true, null]],
        683 => [[['_route' => 'admin_renouvellement_contrat_photographe', '_controller' => 'App\\Controller\\AdminController::createContratPhotographe'], ['photographe_id'], ['POST' => 0], null, false, true, null]],
        716 => [[['_route' => 'admin_acheter_joueur', '_controller' => 'App\\Controller\\AdminController::acheterJoueur'], ['id'], ['POST' => 0], null, false, true, null]],
        754 => [[['_route' => 'admin_get_medical_costs_by_joueur', '_controller' => 'App\\Controller\\AdminController::getMedicalCostsByJoueur'], ['joueurId'], ['GET' => 0], null, false, true, null]],
        787 => [[['_route' => 'fan_app_fan_getfanbyid', '_controller' => 'App\\Controller\\FanController::getFanById'], ['id'], ['GET' => 0], null, false, true, null]],
        818 => [[['_route' => 'fan_app_fan_updatefan', '_controller' => 'App\\Controller\\FanController::updateFan'], ['id'], ['PUT' => 0], null, false, true, null]],
        842 => [[['_route' => 'fan_app_fan_updatefanrevenue', '_controller' => 'App\\Controller\\FanController::updateFanRevenue'], ['id'], ['PUT' => 0], null, false, true, null]],
        874 => [[['_route' => 'fan_app_fan_deletefan', '_controller' => 'App\\Controller\\FanController::deleteFan'], ['id'], ['DELETE' => 0], null, false, true, null]],
        898 => [[['_route' => 'fan_app_fan_deletefanrevenue', '_controller' => 'App\\Controller\\FanController::deleteFanRevenue'], ['id'], ['DELETE' => 0], null, false, true, null]],
        930 => [[['_route' => 'fan_app_fan_getfanrevenue', '_controller' => 'App\\Controller\\FanController::getFanRevenue'], ['id'], ['GET' => 0], null, false, true, null]],
        961 => [[['_route' => 'fan_app_fan_getrevenuesbyfan', '_controller' => 'App\\Controller\\FanController::getRevenuesByFan'], ['id'], ['GET' => 0], null, false, false, null]],
        996 => [[['_route' => 'match_get_joueurs_by_match', '_controller' => 'App\\Controller\\MatchController::getJoueursByMatch'], ['id'], ['GET' => 0], null, false, false, null]],
        1028 => [[['_route' => 'match_create_logistic', '_controller' => 'App\\Controller\\MatchController::createLogistic'], ['matchid'], ['POST' => 0], null, false, true, null]],
        1054 => [[['_route' => 'match_get_logistics_by_match', '_controller' => 'App\\Controller\\MatchController::getLogisticsByMatch'], ['matchId'], ['GET' => 0], null, false, true, null]],
        1071 => [[['_route' => 'match_get_match', '_controller' => 'App\\Controller\\MatchController::getMatch'], ['id'], ['GET' => 0], null, false, true, null]],
        1095 => [[['_route' => 'match_delete_match', '_controller' => 'App\\Controller\\MatchController::deleteMatch'], ['id'], ['DELETE' => 0], null, false, true, null]],
        1126 => [[['_route' => 'match_acheter_ticket', '_controller' => 'App\\Controller\\MatchController::acheterTicket'], ['type'], ['POST' => 0], null, false, true, null]],
        1168 => [[['_route' => 'sponsor_add_sponsor_revenue', '_controller' => 'App\\Controller\\SponsorController::addSponsorRevenue'], ['id'], ['POST' => 0], null, false, true, null]],
        1202 => [
            [['_route' => 'sponsor_get_sponsor_revenues', '_controller' => 'App\\Controller\\SponsorController::getSponsorRevenues'], ['id'], ['GET' => 0], null, false, true, null],
            [null, null, null, null, false, false, 0],
        ],
    ],
    null, // $checkCondition
];
