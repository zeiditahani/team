<?php

namespace App\Controller;

use App\Entity\Sponsor;
use App\Entity\SponsorRevenue;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/sponsor', name: 'sponsor_')]
final class SponsorController extends AbstractController
{
    #[Route('/create-sponsor', name: 'create_sponsor', methods: ['POST'])]
    public function createSponsor(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $sponsor = new Sponsor();
        $sponsor->setNomSociete($data['nom_societe']);
        $sponsor->setAdresse($data['adresse']);
        $sponsor->setEmail($data['email']);
        $sponsor->setPhoneNumber($data['phone_number']);
        $sponsor->setDateDeb(new \DateTime($data['date_deb']));
        $sponsor->setDateFin(new \DateTime($data['date_fin']));
        $sponsor->setPrix($data['prix']);
        $dureeAffichage = \DateTime::createFromFormat('H:i:s', $data['duree_affichage']);
        $sponsor->setDureeAffichage($dureeAffichage);
        $sponsor->setEmplacement($data['emplacement']);
        $sponsor->setLogo($data['logo'] ?? null);
        $sponsor->setStatut("en attente");

        // Ajouter le paiement initial si fourni
        if (isset($data['initial_payment']) && $data['initial_payment'] > 0) {
            $revenue = new SponsorRevenue();
            $revenue -> setSponsor($sponsor);
            $revenue -> setRevenueObtenu($data['initial_payment']);
            $revenue -> setDateEncaissement(new \DateTime());
            $entityManager->persist($revenue);
            $entityManager->flush();
        }

        $entityManager->persist($sponsor);
        $sponsor->verifierStatut();
        $entityManager->flush();

        return new JsonResponse(['message' => 'Sponsor ajouté avec succès !'], 201);
    }

    //------------------------

    #[Route('/list-sponsors', name: 'list_sponsors', methods: ['GET'])]
    public function listSponsors(EntityManagerInterface $entityManager): JsonResponse
    {
        $sponsors = $entityManager->getRepository(Sponsor::class)->findAll();

        if (!$sponsors){
            return new JsonResponse(['message' => 'Vous n\'avez pas des sponsors']);
        }

        $sponsorsArray = [];
        foreach ($sponsors as $sponsor) {
            $sponsorsArray[] = [
                'id' => $sponsor->getId(),
                'nom_societe' => $sponsor->getNomSociete(),
                'statut' => $sponsor->getStatut(),
                'total_revenu' => $sponsor->getTotalRevenu(),
                'prix' => $sponsor->getPrix(),
            ];
        }

        return new JsonResponse($sponsorsArray);
    }

    //---------------------------

    #[Route('/add-revenue/{id}', name: 'add_sponsor_revenue', methods: ['POST'])]
    public function addSponsorRevenue(int $id, Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $sponsor = $entityManager->getRepository(Sponsor::class)->find($id);
        if (!$sponsor) {
            return new JsonResponse(['error' => 'Sponsor non trouvé'], 404);
        }

        $data = json_decode($request->getContent(), true);
        $montant = $data['montant'];

        if (($sponsor->getTotalRevenu() + $montant) > $sponsor->getPrix()) {
            return new JsonResponse(['error' => 'Le montant total dépasse le prix défini'], 400);
        }

        if (($sponsor->getTotalRevenu() + $montant) == $sponsor->getPrix()) {
            $sponsor -> setStatut("payé");
        }

        $revenue = new SponsorRevenue();
        $revenue->setSponsor($sponsor);
        $revenue->setRevenueObtenu($montant);
        $revenue->setDateEncaissement(new \DateTime());
        $entityManager->persist($revenue);
        $entityManager->persist($sponsor);
        $entityManager->flush();

        return new JsonResponse(['message' => 'Paiement enregistré !'], 201);
    }

    //--------------------

    #[Route('/sponsor/revenues/{id}', name: 'get_sponsor_revenues', methods: ['GET'])]
    public function getSponsorRevenues(int $id, EntityManagerInterface $entityManager): JsonResponse
    {
        $sponsor = $entityManager->getRepository(Sponsor::class)->find($id);
        if (!$sponsor) {
            return new JsonResponse(['error' => 'Sponsor non trouvé'], 404);
        }

        $revenus = $sponsor->getRevenus();

        $revenusData = [];
        foreach ($revenus as $revenu) {
            $revenusData[] = [
                'id' => $revenu->getId(),
                'revenueObtenu' => $revenu->getRevenueObtenu(),
                'dateEncaissement' => $revenu->getDateEncaissement()->format('Y-m-d H:i:s'),
            ];
        }

        return new JsonResponse([   
            'sponsor_id' => $sponsor->getId(),
            'nom_societe' => $sponsor->getNomSociete(),
            'somme des paiements' => $sponsor->getTotalRevenu(),
            'statut' => $sponsor->getStatut(),
            'revenus' => $revenusData
        ]);
    }
}
