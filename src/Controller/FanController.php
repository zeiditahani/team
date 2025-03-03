<?php

namespace App\Controller;

use App\Entity\Fan;
use App\Repository\FanRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use JMS\Serializer\SerializerInterface as SerializerSerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Entity\FanRevenue;
use App\Repository\FanRevenueRepository;

#[Route('/api/fan', name: 'fan_')]
class FanController extends AbstractController
{
    //Fan
    //POST /create-fan : Créer un nouveau fan
    #[Route('/create-fan', methods: ['POST'])]
    public function createFan(Request $request, EntityManagerInterface $entityManager,FanRepository $fanRepository, SerializerSerializerInterface $serializer): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $fan = new Fan();
        $fan->setFirstname($data['firstname']);
        $fan->setLastname($data['lastname']);
        $fan->setNumeroTelephone($data['numero_telephone']);
        $fan->setServiceFourni($data['service_fourni']);
        $fan->setDatedeb(new \DateTime($data['datedeb']));
        $fan->setDatefin(new \DateTime($data['datefin']));
        $fan->setPrix($data['prix']);

        // Enregistrement dans la base de données
        $entityManager->persist($fan);
        $entityManager->flush();

        return new JsonResponse($serializer->serialize($fan, 'json'), JsonResponse::HTTP_CREATED,['Content-Type' => 'application/json'],true);
    }
    //GET /listfans : Récupérer la liste de tous les fans
    #[Route('/listfans', methods: ['GET'])]
    public function getAllFans(FanRepository $fanRepository, SerializerSerializerInterface $serializer): JsonResponse
    {
        $fans = $fanRepository->findAll();
        return new JsonResponse($serializer->serialize($fans, 'json'), JsonResponse::HTTP_OK, [], true);
    }
    //GET /listfans/{id} : Récupérer un fan par son ID
    #[Route('/listfans/{id}', methods: ['GET'])]
    public function getFanById(int $id, FanRepository $fanRepository, SerializerSerializerInterface $serializer): JsonResponse
    {
        $fan = $fanRepository->find($id);
    
        if (!$fan) {
            throw new NotFoundHttpException('Fan non trouvé');
        }
    
        return new JsonResponse($serializer->serialize($fan, 'json'), JsonResponse::HTTP_OK, [], true);
    }
    //PUT /update-fan/{id} : Mettre à jour un fan existant.
    #[Route('/update-fan/{id}', methods: ['PUT'])]
    public function updateFan(int $id, Request $request, EntityManagerInterface $entityManager,FanRepository $fanRepository, SerializerSerializerInterface $serializer): JsonResponse
    {
        $fan = $fanRepository->find($id);
    
        if (!$fan) {
            throw new NotFoundHttpException('Fan non trouvé');
        }
    
        $data = json_decode($request->getContent(), true);
    
        $fan->setFirstname($data['firstname']);
        $fan->setLastname($data['lastname']);
        $fan->setNumeroTelephone($data['numero_telephone']);
        $fan->setServiceFourni($data['service_fourni']);
        $fan->setDatedeb(new \DateTime($data['datedeb']));
        $fan->setDatefin(new \DateTime($data['datefin']));
        $fan->setPrix($data['prix']);
    
        $entityManager->persist($fan);
        $entityManager->flush();
    
        return new JsonResponse($serializer->serialize($fan, 'json'), JsonResponse::HTTP_OK, [], true);
    }
    //DELETE /delete-fan/{id} : Supprimer un fan.
    #[Route('/delete-fan/{id}', methods: ['DELETE'])]
    public function deleteFan(int $id, ManagerRegistry $doctrine): JsonResponse
    {
        $em = $doctrine->getManager();
        $fan = $em->getRepository(Fan::class)->find($id);
    
        if (!$fan) {
            throw new NotFoundHttpException('Fan non trouvé');
        }
    
        $em->remove($fan);
        $em->flush();
    
        //return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
        return $this->json(['message' => 'Fan supprimé avec success']);
    }
    
    //FanRevenue

    // POST /fanrevenues : Créer une nouvelle revenue pour un fan
    #[Route('/create-fanrevenue', methods: ['POST'])]
    public function createFanRevenue(Request $request, EntityManagerInterface $entityManager, FanRepository $fanRepository, SerializerSerializerInterface $serializer): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

    $fan = $fanRepository->find($data['fan_id']);
    if (!$fan) {
        throw new NotFoundHttpException('Fan non trouvé');
    }

    $fanRevenue = new FanRevenue();
    $fanRevenue->setRevenueObtenuFan($data['revenue_obtenu_fan']); // Nom exact de la propriété
    $fanRevenue->setDateEncaissement(new \DateTime($data['date_encaissement'])); // Nom exact
    $fanRevenue->setFan($fan);

    $entityManager->persist($fanRevenue);
    $entityManager->flush();

    return new JsonResponse(
        $serializer->serialize($fanRevenue, 'json'), 
        JsonResponse::HTTP_CREATED, 
        ['Content-Type' => 'application/json'], 
        true
    );
    }

    // GET /listfanrevenues : Récupérer toutes les revenues
    #[Route('/listfanrevenues', methods: ['GET'])]
    public function getAllFanRevenues(FanRevenueRepository $fanRevenueRepository, SerializerSerializerInterface $serializer): JsonResponse
    {
        $revenues = $fanRevenueRepository->findAll();
        return new JsonResponse($serializer->serialize($revenues, 'json'), JsonResponse::HTTP_OK, [], true);
    }

    // GET /fanrevenues/{id} : Récupérer une revenue par ID
    #[Route('/fanrevenues/{id}', methods: ['GET'])]
    public function getFanRevenue(int $id, FanRevenueRepository $fanRevenueRepository, SerializerSerializerInterface $serializer): JsonResponse
    {
        $revenue = $fanRevenueRepository->find($id);
    if (!$revenue) {
        throw new NotFoundHttpException('Revenue non trouvée');
    }
    return new JsonResponse($serializer->serialize($revenue, 'json'), JsonResponse::HTTP_OK, [], true);

    }

    // PUT /update-fanrevenue/{id} : Mettre à jour une revenue
    #[Route('/update-fanrevenue/{id}', methods: ['PUT'])]
    public function updateFanRevenue(int $id, Request $request, EntityManagerInterface $entityManager, FanRevenueRepository $fanRevenueRepository, SerializerSerializerInterface $serializer): JsonResponse
    {
        $revenue = $fanRevenueRepository->find($id);
    if (!$revenue) {
        throw new NotFoundHttpException('Revenue non trouvée');
    }

    $data = json_decode($request->getContent(), true);
    
    // Mise à jour avec les bons noms de champs
    if (isset($data['revenue_obtenu_fan'])) {
        $revenue->setRevenueObtenuFan($data['revenue_obtenu_fan']);
    }
    
    if (isset($data['date_encaissement'])) {
        $revenue->setDateEncaissement(new \DateTime($data['date_encaissement']));
    }

    $entityManager->flush();

    return new JsonResponse($serializer->serialize($revenue, 'json'), JsonResponse::HTTP_OK, [], true);
    }

    // DELETE /delete-fanrevenue/{id} : Supprimer une revenue
    #[Route('/delete-fanrevenue/{id}', methods: ['DELETE'])]
    public function deleteFanRevenue(int $id, EntityManagerInterface $entityManager, FanRevenueRepository $fanRevenueRepository): JsonResponse
    {
        $revenue = $fanRevenueRepository->find($id);
    if (!$revenue) {
        throw new NotFoundHttpException('Revenue non trouvée');
    }

    $entityManager->remove($revenue);
    $entityManager->flush();

    return $this->json(['message' => 'Revenue supprimée avec succès']);

    }
    //API Relationnelle entre Fan et FanRevenue

    // GET /fans/{id}/listrevenues : Récupérer toutes les revenues pour un fan spécifique.
    #[Route('/fans/{id}/listrevenues', methods: ['GET'])]
    public function getRevenuesByFan(int $id, FanRepository $fanRepository, FanRevenueRepository $fanRevenueRepository, SerializerSerializerInterface $serializer): JsonResponse
    {
        $fan = $fanRepository->find($id);
        if (!$fan) {
            throw new NotFoundHttpException('Fan non trouvé');
        }
    
        $revenues = $fanRevenueRepository->findBy(['fan' => $fan]);
        return new JsonResponse($serializer->serialize($revenues, 'json'), JsonResponse::HTTP_OK, [], true);    
    }

    // POST /fans/{id}/listrevenues : Ajouter une revenue à un fan spécifique.
    #[Route('/fans/{id}/listrevenues', methods: ['POST'])]
    public function addRevenueToFan(int $id, Request $request, EntityManagerInterface $entityManager, FanRepository $fanRepository, SerializerSerializerInterface $serializer): JsonResponse
    {
        $fan = $fanRepository->find($id);
    if (!$fan) {
        throw new NotFoundHttpException('Fan non trouvé');
    }

    $data = json_decode($request->getContent(), true);
    
    $fanRevenue = new FanRevenue();
    $fanRevenue->setRevenueObtenuFan($data['revenue_obtenu_fan']);
    $fanRevenue->setDateEncaissement(new \DateTime($data['date_encaissement']));
    $fanRevenue->setFan($fan);

    // Utilisez la méthode addFanRevenue de l'entité Fan pour la relation bidirectionnelle
    $fan->addFanRevenue($fanRevenue);

    $entityManager->persist($fanRevenue);
    $entityManager->flush();

    return new JsonResponse(
        $serializer->serialize($fanRevenue, 'json'), 
        JsonResponse::HTTP_CREATED, 
        ['Content-Type' => 'application/json'], 
        true
    );
    }
}
