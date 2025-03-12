<?php

namespace App\Controller;

use App\Entity\EquipesAdversaires;
use App\Entity\Joueur;
use App\Entity\Logistic;
use App\Entity\Matchs;
use App\Entity\Ticket;
use Doctrine\ORM\EntityManagerInterface;
use Stripe\PaymentIntent;
use Stripe\Stripe;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/match', name: 'match_')]
final class MatchController extends AbstractController
{
    #[Route('/create', name: 'create_match', methods: ['POST'])]
    public function createMatch(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['date'], $data['terrain'], $data['equipeAdverse'], $data['tactique'], $data['joueurs'])) {
            return new JsonResponse(['error' => 'Données incomplètes'], 400);
        }

        $tactiques = [
            "4-3-3" => ["attaquant" => 3, "milieu" => 3, "défenseur" => 4, "gardien" => 1],
            "4-4-2" => ["attaquant" => 2, "milieu" => 4, "défenseur" => 4, "gardien" => 1],
            "3-5-2" => ["attaquant" => 2, "milieu" => 5, "défenseur" => 3, "gardien" => 1]
        ];

        if (!array_key_exists($data['tactique'], $tactiques)) {
            return new JsonResponse(['error' => 'Tactique invalide'], 400);
        }

        $equipeAdverse = $entityManager->getRepository(EquipesAdversaires::class)
                                    ->findOneBy(['nom' => $data['equipeAdverse']]);

        if (!$equipeAdverse) {
            $equipeAdverse = new EquipesAdversaires();
            $equipeAdverse->setNom($data['equipeAdverse']);
            $entityManager->persist($equipeAdverse);
        }

        // Vérification du nombre de joueurs
        $positionsRequises = $tactiques[$data['tactique']];
        $positionsSelectionnees = ["attaquant" => 0, "milieu" => 0, "défenseur" => 0, "gardien" => 0];
        $joueursSelectionnes = []; // Tableau pour suivre les joueurs sélectionnés

        $match = new Matchs();
        $match->setDate(new \DateTime($data['date']));
        $match->setTerrain($data['terrain']);
        $match->setEquipeAdverse($equipeAdverse);
        $match->setTactique($data['tactique']);
        $entityManager->persist($match);

        foreach ($data['joueurs'] as $joueurData) {
            $joueur = $entityManager->getRepository(Joueur::class)->find($joueurData['id']);

            if (!$joueur) {
                return new JsonResponse(['error' => "Joueur ID {$joueurData['id']} non trouvé"], 404);
            }

            if (in_array($joueur->getId(), $joueursSelectionnes)) {
                return new JsonResponse(['error' => "Joueur ID {$joueurData['id']} est déjà sélectionné pour ce match"], 400);
            }

            if ($positionsSelectionnees[$joueurData['position']] >= $positionsRequises[$joueurData['position']]) {
                return new JsonResponse(['error' => "Nombre maximum de {$joueurData['position']} atteint"], 400);
            }

            // Vérifier la disponibilité du joueur
            $matchLeMemeJour = $entityManager->getRepository(Matchs::class)
                                            ->createQueryBuilder('m')
                                            ->join('m.joueurs', 'j')
                                            ->where('j.id = :joueurId')
                                            ->andWhere('m.date = :dateMatch')
                                            ->setParameter('joueurId', $joueur->getId())
                                            ->setParameter('dateMatch', new \DateTime($data['date']))
                                            ->getQuery()
                                            ->getOneOrNullResult();

            if ($matchLeMemeJour) {
                return new JsonResponse(['error' => "Joueur ID {$joueurData['id']} est déjà pris ce jour-là"], 400);
            }

            $match->addJoueur($joueur, $joueurData['position']);
            $positionsSelectionnees[$joueurData['position']]++;
            $joueursSelectionnes[] = $joueur->getId();
        }

        $totalJoueurs = array_sum($positionsSelectionnees);
        if ($totalJoueurs !== 11) {
            return new JsonResponse(['error' => 'Le nombre total de joueurs doit être de 11'], 400);
        }

        // Création des Tickets (Pelouse & Virage)
        foreach (['Pelouse' => 20.0, 'Virage' => 15.0] as $type => $prix) {
            $ticket = new Ticket();
            $ticket->setMatch($match);
            $ticket->setType($type);
            $ticket->setNbTicketDispo(100);
            $ticket->setNbTicketTotal(100);
            $ticket->setStatut("disponible");
            $ticket->setPrix($prix);
            $entityManager->persist($ticket);
        }

        // Ajout des Logistiques
        foreach ($data['logistiques'] as $logData) {
            $logistic = new Logistic();
            $logistic->setMatch($match);
            $logistic->setType($logData['type']);
            $logistic->setDepense($logData['depense']);
            $entityManager->persist($logistic);
        }

        $entityManager->flush();
        return new JsonResponse(['message' => 'Match créé avec succès'], 201);
    }

    //--------------

    #[Route('/{id}/joueurs', name: 'get_joueurs_by_match', methods: ['GET'])]
    public function getJoueursByMatch(int $id, EntityManagerInterface $entityManager): JsonResponse
    {
        $match = $entityManager->getRepository(Matchs::class)->find($id);

        if (!$match) {
            return new JsonResponse(['error' => 'Match non trouvé'], 404);
        }

        $joueurs = $match->getJoueurs(); 

        if (empty($joueurs)) {
            return new JsonResponse(['message' => 'Aucun joueur trouvé pour ce match'], 200);
        }

        $joueursData = array_map(function ($joueur) {
            return [
                'id' => $joueur->getId(),
                'nom complet' => $joueur->getFirstname(). ' ' . $joueur->getLastname(),
                'position' => $joueur->getPosition(),
            ];
        }, $joueurs->toArray()); 

        return new JsonResponse([
            'message' => 'Liste des joueurs récupérée avec succès',
            'joueurs' => $joueursData
        ], 200);
    }

    //-----------

    #[Route('/ajout-logistic/{matchid}', name: 'create_logistic', methods: ['POST'])]
    public function createLogistic(int $matchid, Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        // Récupérer les données depuis la requête
        $data = json_decode($request->getContent(), true);

        // Vérifier que les données nécessaires sont présentes
        if (!isset($data['type'], $data['depense'])) {
            return new JsonResponse(['error' => 'Données manquantes: type et dépense nécessaires'], 400);
        }

        // Vérifier que le montant de la dépense est valide
        if (!is_numeric($data['depense']) || $data['depense'] <= 0) {
            return new JsonResponse(['error' => 'Le montant de la dépense doit être un nombre positif'], 400);
        }

        // Vérifier si le match existe
        $match = $entityManager->getRepository(Matchs::class)->find($matchid);

        if (!$match) {
            return new JsonResponse(['error' => 'Match non trouvé'], 404);
        }

        // Créer une nouvelle entité Logistic
        $logistic = new Logistic();
        $logistic->setMatch($match);
        $logistic->setType($data['type']);
        $logistic->setDepense($data['depense']);

        // Persister la dépense logistique dans la base de données
        $entityManager->persist($logistic);
        $entityManager->flush();

        // Retourner une réponse de succès
        return new JsonResponse([
            'message' => 'Dépense logistique ajoutée avec succès',
            'logistic' => [
                'type' => $logistic->getType(),
                'depense' => $logistic->getDepense(),
                'match_id' => $match->getId()
            ]
        ], 201);
    }

    //--------------

    #[Route('/logistic/{matchId}', name: 'get_logistics_by_match', methods: ['GET'])]
    public function getLogisticsByMatch(int $matchId, EntityManagerInterface $entityManager): JsonResponse
    {
        // Récupérer le match avec l'ID spécifié
        $match = $entityManager->getRepository(Matchs::class)->find($matchId);

        // Vérifier si le match existe
        if (!$match) {
            return new JsonResponse(['error' => 'Match non trouvé'], 404);
        }

        // Récupérer toutes les dépenses logistiques associées au match
        $logistics = $entityManager->getRepository(Logistic::class)->findBy(['match' => $match]);

        // Si aucune dépense logistique n'est trouvée, retourner un message approprié
        if (empty($logistics)) {
            return new JsonResponse(['message' => 'Aucune dépense logistique trouvée pour ce match'], 200);
        }

        // Calculer la somme des dépenses
        $totalDepense = array_reduce($logistics, function ($sum, Logistic $logistic) {
            return $sum + $logistic->getDepense();
        }, 0);

        // Préparer la réponse avec les données des dépenses logistiques
        $logisticsData = array_map(function (Logistic $logistic) {
            return [
                'type' => $logistic->getType(),
                'depense' => $logistic->getDepense(),
            ];
        }, $logistics);

        // Retourner la réponse avec les dépenses logistiques et la somme totale
        return new JsonResponse([
            'message' => 'Dépenses logistiques récupérées avec succès',
            'total_depense' => $totalDepense,
            'logistics' => $logisticsData
        ], 200);
    }

    

    //--------------


    #[Route('/list', name: 'list_matches', methods: ['GET'])]
    public function listMatches(EntityManagerInterface $entityManager): JsonResponse
    {
        // Récupérer tous les matchs
        $matches = $entityManager->getRepository(Matchs::class)->findAll();

        // Si aucun match n'est trouvé, retourner un message vide
        if (empty($matches)) {
            return new JsonResponse(['message' => 'Aucun match trouvé'], 404);
        }

        // Récupérer les données des matchs
        $matchesData = [];
        foreach ($matches as $match) {
            $matchesData[] = [
                'id' => $match->getId(),
                'date' => $match->getDate()->format('Y-m-d H:i:s'),
                'terrain' => $match->getTerrain(),
                'equipeAdverse' => $match->getEquipeAdverse()->getNom(),
                'tactique' => $match->getTactique(),
            ];
        }

        // Retourner la liste des matchs en réponse
        return new JsonResponse($matchesData);
    }

    //-----------------------

    #[Route('/{id}', name: 'get_match', methods: ['GET'])]
    public function getMatch(int $id, EntityManagerInterface $entityManager): JsonResponse
    {
        // Récupérer le match par son ID
        $match = $entityManager->getRepository(Matchs::class)->find($id);

        // Si le match n'existe pas, retourner une erreur
        if (!$match) {
            return new JsonResponse(['error' => 'Match non trouvé'], 404);
        }

        // Récupérer les détails du match
        $matchData = [
            'id' => $match->getId(),
            'date' => $match->getDate()->format('Y-m-d H:i:s'),
            'terrain' => $match->getTerrain(),
            'equipeAdverse' => $match->getEquipeAdverse()->getNom(),
            'tactique' => $match->getTactique(),
            'joueurs' => [],
        ];

        // Ajouter les joueurs du match
        foreach ($match->getJoueurs() as $joueur) {
            $matchData['joueurs'][] = [
                'id' => $joueur->getId(),
                'nom' => $joueur->getFirstname(). ' ' .$joueur->getLastname(),
                'position' => $joueur->getPosition(),
            ];
        }

        // Ajouter les tickets du match
        $matchData['tickets'] = [];
        foreach ($match->getTickets() as $ticket) {
            $matchData['tickets'][] = [
                'type' => $ticket->getType(),
                'prix' => $ticket->getPrix(),
                'nbTicketDispo' => $ticket->getNbTicketDispo(),
                'statut' => $ticket->getStatut(),
            ];
        }

        // Ajouter les logistiques du match
        $matchData['logistiques'] = [];
        foreach ($match->getLogistics() as $logistic) {
            $matchData['logistiques'][] = [
                'type' => $logistic->getType(),
                'depense' => $logistic->getDepense(),
            ];
        }

        return new JsonResponse($matchData);
    }
    ///-------------------

    #[Route('/delete/{id}', name: 'delete_match', methods: ['DELETE'])]
    public function deleteMatch(int $id, EntityManagerInterface $entityManager): JsonResponse
    {
        // Récupérer le match à supprimer
        $match = $entityManager->getRepository(Matchs::class)->find($id);

        if (!$match) {
            return new JsonResponse(['error' => 'Match non trouvé'], 404);
        }

        // Supprimer les tickets associés au match
        foreach ($match->getTickets() as $ticket) {
            $entityManager->remove($ticket);
        }

        // Supprimer les logistiques associées au match
        foreach ($match->getLogistics() as $logistic) {
            $entityManager->remove($logistic);
        }

        // Supprimer les joueurs associés au match (facultatif selon votre logique)
        foreach ($match->getJoueurs() as $joueur) {
            // Si vous ne souhaitez pas garder les joueurs liés au match, vous pouvez les dissocier ici
            $match->removeJoueur($joueur);
        }

        // Supprimer le match lui-même
        $entityManager->remove($match);

        // Appliquer toutes les suppressions
        $entityManager->flush();

        return new JsonResponse(['message' => 'Match supprimé avec succès'], 200);
    }

    //------------------

    #[Route('/achat-ticket/{type}', name: 'acheter_ticket', methods: ['POST'])]
    public function acheterTicket(string $type, Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        // Récupérer le ticket à acheter selon le type (Pelouse ou Virage)
        $ticket = $entityManager->getRepository(Ticket::class)->findOneBy(['type' => $type]);

        if (!$ticket) {
            return new JsonResponse(['error' => 'Ticket non trouvé pour ce type'], 404);
        }

        // Vérifier si des tickets sont disponibles
        if ($ticket->getNbTicketDispo() <= 0) {
            return new JsonResponse(['error' => 'Aucun ticket disponible'], 400);
        }

        // Récupérer le token et les informations de paiement depuis la requête
        $data = json_decode($request->getContent(), true);

        if (!isset($data['token'], $data['nom'], $data['email'])) {
            return new JsonResponse(['error' => 'Données manquantes'], 400);
        }

        // Initialiser Stripe avec ta clé API
        Stripe::setApiKey($_ENV['STRIPE_SECRET_KEY']);

        try {
            // Créer un PaymentIntent
            $paymentIntent = PaymentIntent::create([
                'amount' => $ticket->getPrix() *100,  // Montant en centimes
                'currency' => 'usd',  // La devise (tu peux utiliser 'eur' ou 'tnd' selon ta configuration)
                'payment_method' => $data['token'],  // Utiliser le token généré par Stripe
                //'confirmation_method' => 'manual',
                'confirm' => true,
                'automatic_payment_methods' => [
                    'enabled' => true,  // Active la gestion automatique des méthodes de paiement
                    'allow_redirects' => 'never'  // Empêche les redirections
                ],
            ]);

            // Vérification du statut du paiement
            if ($paymentIntent->status !== 'succeeded') {
                return new JsonResponse(['error' => 'Le paiement a échoué'], 400);
            }

            // Décrémenter le nombre de tickets disponibles
            $ticket->setNbTicketDispo($ticket->getNbTicketDispo() - 1);
            $reference = $this->genererReferenceAchat();

            // Si plus de tickets disponibles, mettre à jour le statut du ticket à "rupture"
            if ($ticket->getNbTicketDispo() == 0) {
                $ticket->setStatut("rupture");
            }

            $entityManager->flush();

            

            return new JsonResponse([
                'message' => 'Achat de ticket effectué avec succès, veuillez garder votre référence !',
                'ticket' => [
                    'type' => $ticket->getType(),
                    'prix' => $ticket->getPrix(),
                    'reference' => $reference,
                    'nom' => $data['nom'],
                    'email' => $data['email'],
                    'payment_status' => $paymentIntent->status
                ]
            ], 200);

        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], 400);
        }
    }

    private function genererReferenceAchat(): string
    {
        // Utilisation de uniqid() pour générer une référence unique à base de l'ID du ticket et de la date
        return 'REF' . strtoupper(uniqid(date('YmdHis')));
    }





    //---------- A FAIRE DANS LE EQUIPE CONTROLLER ------------------//

    #[Route('/equipe-adversaire/list', name: 'get_equipes_adverses_list', methods: ['GET'])]
    public function getEquipesAdversesList(EntityManagerInterface $entityManager): JsonResponse
    {
        $equipesAdverses = $entityManager->getRepository(EquipesAdversaires::class)->findAll();

        if (empty($equipesAdverses)) {
            return new JsonResponse(['message' => 'Aucune équipe adverse trouvée'], 200);
        }

        $equipesData = array_map(function ($equipe) {
            return [
                'id' => $equipe->getId(),
                'nom' => $equipe->getNom(),
            ];
        }, $equipesAdverses);

        return new JsonResponse([
            'message' => 'Liste des équipes adverses récupérée avec succès',
            'equipesAdverses' => $equipesData
        ], 200);
    }

    //---------- A FAIRE DANS LE EQUIPE CONTROLLER ------------------//


    


}
