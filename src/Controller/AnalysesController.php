<?php

namespace App\Controller;

use App\Entity\Analyses;
use App\Entity\Matchs;
use App\Entity\Joueur;
use App\Repository\AnalysesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


#[Route('/api/analyses')]
class AnalysesController extends AbstractController
{
    #[Route('/create-analyse', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        // Vérification des données requises avec des messages spécifiques
        if (!isset($data['match_id'])) {
            return new JsonResponse(['error' => 'Le champ match_id est requis'], 400);
        }
    
        if (!isset($data['joueur_id'])) {
            return new JsonResponse(['error' => 'Le champ joueur_id est requis'], 400);
        }
    
        if (!isset($data['nb_carton_rouge'])) {
            return new JsonResponse(['error' => 'Le champ nb_carton_rouge est requis'], 400);
        }
    
        if (!isset($data['nb_carton_jaune'])) {
            return new JsonResponse(['error' => 'Le champ nb_carton_jaune est requis'], 400);
        }
    
        // Vérification de l'existence du match et du joueur
        $match = $entityManager->getRepository(Matchs::class)->find($data['match_id']);
        if (!$match) {
            return new JsonResponse(['error' => 'Le match spécifié n\'existe pas'], 404);
        }
    
        $joueur = $entityManager->getRepository(Joueur::class)->find($data['joueur_id']);
        if (!$joueur) {
            return new JsonResponse(['error' => 'Le joueur spécifié n\'existe pas'], 404);
        }
    
        // Création de l'analyse
        $analyse = new Analyses();
        $analyse->setMatch($match);
        $analyse->setJoueur($joueur);
        $analyse->setNbCartonRouge($data['nb_carton_rouge']);
        $analyse->setNbCartonJaune($data['nb_carton_jaune']);
    
        $entityManager->persist($analyse);
    
        // Mise à jour des cartons du joueur
        $joueur->setNbCartonRouge($joueur->getNbCartonRouge() + $data['nb_carton_rouge']);
        $joueur->setNbCartonJaune($joueur->getNbCartonJaune() + $data['nb_carton_jaune']);
    
        $entityManager->persist($joueur);
        $entityManager->flush();
    
        return new JsonResponse(['message' => 'Analyse créée avec succès et joueur mis à jour'], 201);
    }


    #[Route('/update-analyse/{id}', methods: ['PUT'])]
    public function update(int $id, Request $request, EntityManagerInterface $entityManager, AnalysesRepository $analysesRepository): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
    $analyse = $analysesRepository->find($id);

    if (!$analyse) {
        return new JsonResponse(['error' => 'Analyse introuvable'], 404);
    }

    $joueur = $analyse->getJoueur();
    if (!$joueur) {
        return new JsonResponse(['error' => 'Joueur introuvable pour cette analyse'], 404);
    }

    // Sauvegarde des valeurs actuelles
    $ancienNbCartonRouge = $analyse->getNbCartonRouge();
    $ancienNbCartonJaune = $analyse->getNbCartonJaune();

    // Mise à jour de l'analyse et calcul des différences
    if (isset($data['nb_carton_rouge'])) {
        $nouveauNbCartonRouge = $data['nb_carton_rouge'];
        $diffCartonRouge = $nouveauNbCartonRouge - $ancienNbCartonRouge;
        $analyse->setNbCartonRouge($nouveauNbCartonRouge);
        $joueur->setNbCartonRouge($joueur->getNbCartonRouge() + $diffCartonRouge);
    }

    if (isset($data['nb_carton_jaune'])) {
        $nouveauNbCartonJaune = $data['nb_carton_jaune'];
        $diffCartonJaune = $nouveauNbCartonJaune - $ancienNbCartonJaune;
        $analyse->setNbCartonJaune($nouveauNbCartonJaune);
        $joueur->setNbCartonJaune($joueur->getNbCartonJaune() + $diffCartonJaune);
    }

    $entityManager->flush();

    return new JsonResponse(['message' => 'Analyse mise à jour avec succès']);
    }

}
