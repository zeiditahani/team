<?php

namespace App\Controller;

use App\Entity\Equipe;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/equipe', name: 'equipe_')]
final class EquipeController extends AbstractController
{
    #[Route('/create-equipe', name: 'create_equipe', methods: ['POST'])]
    public function createEquipe(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['nom'], $data['date_fondation'])) {
            return new JsonResponse(['error' => 'Nom et date_fondation sont requis'], 400);
        }

        // Création de l'équipe
        $equipe = new Equipe();
        $equipe->setNom($data['nom']);
        $equipe->setDateFondation(new \DateTime($data['date_fondation']));

        // Sauvegarde en base
        $entityManager->persist($equipe);
        $entityManager->flush();

        return new JsonResponse([
            'message' => 'Équipe créée avec succès',
            'id' => $equipe->getId(),
            'nom' => $equipe->getNom(),
            'date_fondation' => $equipe->getDateFondation()->format('Y-m-d'),
        ], 201);
    }

    //-------------------------------------------------

    #[Route('/informations', name: 'get_equipe', methods: ['GET'])]
    public function getEquipe(ManagerRegistry $doctrine): JsonResponse
    {
        $equipe = $doctrine->getRepository(Equipe::class)->findOneBy([]);

        if (!$equipe) {
            return $this->json(['message' => 'Aucune équipe trouvée'], JsonResponse::HTTP_NOT_FOUND);
        }

        // Récupérer les joueurs
        $joueurs = [];
        foreach ($equipe->getJoueurs() as $joueur) {
            $joueurs[] = [
                'id' => $joueur->getId(),
                'firstname' => $joueur->getFirstname(),
                'lastname' => $joueur->getLastname(),
                'position' => $joueur->getPosition(),
                'numero_maillot' => $joueur->getNumeroMaillot(),
            ];
        }

        // Récupérer les entraîneurs
        $entraineurs = [];
        foreach ($equipe->getEntraineurs() as $entraineur) {
            $entraineurs[] = [
                'id' => $entraineur->getId(),
                'firstname' => $entraineur->getFirstname(),
                'lastname' => $entraineur->getLastname(),
                'specialite' => $entraineur->getSpecialite(),
            ];
        }

        // Récupérer les médecins
        $medecins = [];
        foreach ($equipe->getMedecins() as $medecin) {
            $medecins[] = [
                'id' => $medecin->getId(),
                'firstname' => $medecin->getFirstname(),
                'lastname' => $medecin->getLastname(),
                'specialite' => $medecin->getSpecialite(),
            ];
        }

        $kines = [];
        foreach ($equipe->getKines() as $kine) {
            $kines[] = [
                'id' => $kine->getId(),
                'firstname' => $kine->getFirstname(),
                'lastname' => $kine->getLastname(),
            ];
        }

        // Récupérer les photographes
        $photographes = [];
        foreach ($equipe->getPhotographes() as $photographe) {
            $photographes[] = [
                'id' => $photographe->getId(),
                'firstname' => $photographe->getFirstname(),
                'lastname' => $photographe->getLastname(),
            ];
        }

        // Récupérer le président
        $president = $equipe->getPresident();
        $presidentData = $president ? [
            'id' => $president->getId(),
            'firstname' => $president->getFirstname(),
            'lastname' => $president->getLastname(),
        ] : null;

        // Construire la réponse JSON
        $response = [
            'id' => $equipe->getId(),
            'nom' => $equipe->getNom(),
            'date_fondation' => $equipe->getDateFondation(),
            'joueurs' => $joueurs,
            'entraineurs' => $entraineurs,
            'medecins' => $medecins,
            'kines' => $kines,
            'photographes' => $photographes,
            'president' => $presidentData,
        ];

        return $this->json($response);
    }

    //--------------------------

    #[Route('/update-president', name: 'update_president', methods: ['PUT'])]
    public function updatePresident(Request $request, ManagerRegistry $doctrine): JsonResponse
    {
        $em = $doctrine->getManager();
        $data = json_decode($request->getContent(), true);

        // Récupérer l'équipe unique
        $equipe = $doctrine->getRepository(Equipe::class)->findOneBy([]);
        if (!$equipe) {
            return $this->json(['message' => 'Aucune équipe trouvée'], JsonResponse::HTTP_NOT_FOUND);
        }

        // Vérifier si un président actuel existe
        $ancienPresident = $equipe->getPresident();
        if ($ancienPresident) {
            $ancienUser = $ancienPresident->getUser();
            
            // Supprimer l'utilisateur de la base tout en gardant le président
            if ($ancienUser) {
                $ancienPresident->setUser(null); // Dissocier le User du President
                $em->remove($ancienUser);
                $em->flush();
            }

            // Mettre le président actuel de l'équipe à NULL
            $equipe->setPresident(null);
            
            // Mettre à jour la relation inverse dans President
            $ancienPresident->setEquipe(null);

            // Sauvegarder ces modifications avant d'affecter un nouveau président
            $em->persist($equipe);
            $em->persist($ancienUser);
            $em->persist($ancienPresident);
            $em->flush();
            return $this->json(['message' => 'L\'ancien président a été désactivé et l\'équipe est prête pour un nouveau président']);
            
            
        }

        else{
            return $this->json(['message' => 'L\'equipe n\'a pas un president']);
        }
    }
}
