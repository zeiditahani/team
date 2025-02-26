<?php

namespace App\Controller;

use App\Entity\ContratEntraineur;
use App\Entity\ContratJoueur;
use App\Entity\ContratKine;
use App\Entity\ContratMedecin;
use App\Entity\ContratPhotographe;
use App\Entity\ContratPresident;
use App\Entity\Entraineur;
use App\Entity\Equipe;
use App\Entity\Joueur;
use App\Entity\Kine;
use App\Entity\User;
use App\Entity\Medecin;
use App\Entity\Photographe;
use App\Entity\President;
use App\Entity\TalentDataBase;
use App\Entity\Task;
use App\Entity\Trainingsession;
use App\Entity\MedicalCost;
use App\Repository\EquipeRepository;
use App\Repository\KineRepository;
use App\Repository\TalentDataBaseRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\Persistence\ManagerRegistry;

#[Route('/api/admin', name: 'admin_')]
class AdminController extends AbstractController
{
    #[Route('/create-medecin', name: 'create_medecin', methods: ['POST'])]
    public function createMedecin(Request $request, UserPasswordHasherInterface $passwordHasher, ManagerRegistry $doctrine): JsonResponse
    {
        $em = $doctrine->getManager();
        $data = json_decode($request->getContent(), true);

        $email = $data['email'];
        $password = $data['password'];
        $firstname = $data['firstname'];
        $lastname = $data['lastname'];
        $birthdate = new \DateTime($data['birthdate']);
        $specialite = $data['specialite'];
        $salaire = $data['salaire'];
        $phone_number = $data['phone_number'];
        $dateAffectation = new \DateTime($data['date_affectation']);
        $dateFinContrat = isset($data['date_fin_contrat']) && !empty($data['date_fin_contrat']) ? new \DateTime($data['date_fin_contrat']) : null;

        // Récupérer l'équipe unique
        $equipe = $doctrine->getRepository(Equipe::class)->findOneBy([]);
        if (!$equipe) {
            return $this->json(['message' => 'Aucune équipe trouvée'], JsonResponse::HTTP_NOT_FOUND);
        }

        // Créer l'utilisateur
        $user = new User();
        $user->setEmail($email);
        $user->setUsername($email);
        $hashedPassword = $passwordHasher->hashPassword($user, $password);
        $user->setPassword($hashedPassword);
        $user->setRoles(['ROLE_MEDECIN']);

        // Créer le médecin
        $medecin = new Medecin();
        $medecin->setFirstname($firstname);
        $medecin->setLastname($lastname);
        $medecin->setBirthdate($birthdate);
        $medecin->setSpecialite($specialite);
        $medecin->setPhoneNumber($phone_number);
        $medecin->setUser($user);

        // Lier le médecin à l'équipe
        $medecin->setEquipe($equipe);

        // Ajouter le médecin à l'équipe
        $equipe->addMedecin($medecin);

        $contrat = new ContratMedecin();
        $contrat->setMedecin($medecin);
        $contrat->setSalaire($salaire);
        $contrat->setDateAffectation($dateAffectation);
        $contrat->setDateFinContrat($dateFinContrat);
        $contrat->setStatut(true);

        $medecin->addContrat($contrat);

        // Sauvegarder l'utilisateur et le médecin
        $em->persist($user);
        $em->persist($medecin);
        $em->persist($equipe);
        $em->persist($contrat);
        $em->flush();

        return $this->json(['message' => 'Médecin et son contrat créés et affecté à l\'équipe avec succès']);
    }

    //**************************************

    #[Route('/update-medecin/{id}', name: 'update_medecin', methods: ['PUT'])]
    public function updateMedecin(
        int $id, 
        Request $request, 
        UserPasswordHasherInterface $passwordHasher, 
        ManagerRegistry $doctrine
    ): JsonResponse {
        $em = $doctrine->getManager();
        $data = json_decode($request->getContent(), true);

        // Récupérer le médecin
        $medecin = $em->getRepository(Medecin::class)->find($id);
        if (!$medecin) {
            return $this->json(['message' => 'Médecin non trouvé'], 404);
        }

        // Récupérer l'utilisateur lié
        $user = $medecin->getUser();
        
        // Mise à jour des informations du médecin
        if (isset($data['phone_number'])) $medecin->setPhoneNumber($data['phone_number']);

        // Mise à jour de l'email et du mot de passe de l'utilisateur
        if (isset($data['email'])) $user->setEmail($data['email']);
        if (isset($data['password']) && !empty($data['password'])) {
            $hashedPassword = $passwordHasher->hashPassword($user, $data['password']);
            $user->setPassword($hashedPassword);
        }

        $em->flush();

        return $this->json(['message' => 'Médecin mis à jour avec succès']);
    }

    //**************************************

    #[Route('/listmedecins', name: 'listmedecins', methods: ['GET'])]
    public function listMedecins(ManagerRegistry $doctrine): JsonResponse
    {
        $medecins = $doctrine->getRepository(Medecin::class)->findAll();
        
        $medecinData = [];
        foreach ($medecins as $medecin) {
            // Récupérer les contrats du médecin
            $contratsData = [];
            foreach ($medecin->getContrats() as $contrat) {
                $contratsData[] = [
                    'id' => $contrat->getId(),
                    'salaire' => $contrat->getSalaire(),
                    'date_affectation' => $contrat->getDateAffectation()->format('Y-m-d'),
                    'date_fin_contrat' => $contrat->getDateFinContrat() ? $contrat->getDateFinContrat()->format('Y-m-d') : null,
                    'statut' => $contrat->isStatut(),
                ];
            }

            // Ajouter les informations du médecin et ses contrats
            $medecinData[] = [
                'id' => $medecin->getId(),
                'firstname' => $medecin->getFirstname(),
                'lastname' => $medecin->getLastname(),
                'birthdate' => $medecin->getBirthdate()->format('Y-m-d'),
                'specialite' => $medecin->getSpecialite(),
                'phone_number' => $medecin->getPhoneNumber(),
                'contrats' => $contratsData, // Ajout des contrats
            ];
        }
        
        return $this->json($medecinData);
    }

    //****************************** 

    #[Route('/desactiver-contrat-medecin/{medecin_id}', name: 'desactiver_contrat_medecin', methods: ['PATCH'])]
    public function desactiverContratMedecin(
        int $medecin_id,
        ManagerRegistry $doctrine
    ): JsonResponse {
        $em = $doctrine->getManager();

        // Récupérer le joueur par ID
        $medecin = $doctrine->getRepository(Medecin::class)->find($medecin_id);
        if (!$medecin) {
            return $this->json(['message' => 'Medecin non trouvé'], 404);
        }

        // Récupérer le contrat actuel
        $contrat = $medecin->getContrats()->filter(function($contrat) {
            return $contrat->isStatut() === true; // Vérifie que le statut est actif
        })->first();
        
        if (!$contrat) {
            return $this->json(['message' => 'Le medecin n\'a pas de contrat actif'], 404);
        }

        // Désactiver le contrat
        $contrat->setStatut(false);

        $medecin->setEquipe(null);

        // Sauvegarder les modifications
        $em->flush();

        return $this->json(['message' => 'Contrat du medecin désactivé avec succès']);
    }

    //****************************** 

    #[Route('/renouvellement-contrat-medecin/{medecin_id}', name: 'renouvellement_contrat-medecin', methods: ['POST'])]
    public function createContratMedecin(
        int $medecin_id,
        Request $request,
        ManagerRegistry $doctrine
    ): JsonResponse {
        $em = $doctrine->getManager();
        $data = json_decode($request->getContent(), true);

        // Récupérer le joueur par ID
        $medecin = $doctrine->getRepository(Medecin::class)->find($medecin_id);
        if (!$medecin) {
            return $this->json(['message' => 'Medecin non trouvé'], 404);
        }

        // Récupérer l'ancien contrat actif
        $ancienContrat = $medecin->getContrats()->filter(function($contrat) {
            return $contrat->isStatut() === true; // Seules les contrats actifs sont considérés
        })->first();

        // Vérification si un contrat actif existe
        if ($ancienContrat) {
            // Vérifier si la date actuelle est après la date de fin du contrat
            $currentDate = new \DateTime();
            $dateFinContrat = $ancienContrat->getDateFinContrat();

            if ($currentDate <= $dateFinContrat) {
                return $this->json([
                    'message' => 'Le contrat actuel est encore valide, vous ne pouvez pas renouveler tant qu\'il est en cours.'
                ], 400);
            }
        }

        // Créer un nouveau contrat
        $nouveauContrat = new ContratMedecin();
        $nouveauContrat->setSalaire($data['salaire']);
        $nouveauContrat->setDateAffectation(new \DateTime($data['date_affectation']));
        $nouveauContrat->setDateFinContrat(new \DateTime($data['date_fin_contrat']));
        $nouveauContrat->setStatut(true);  // Le statut est actif par défaut
        $medecin->addContrat($nouveauContrat);

        // Sauvegarder le nouveau contrat
        $em->persist($nouveauContrat);
        $em->flush();

        return $this->json([
            'message' => 'Nouveau contrat créé avec succès pour le medecin',
            'contrat' => [
                'salaire' => $nouveauContrat->getSalaire(),
                'date_affectation' => $nouveauContrat->getDateAffectation()->format('Y-m-d'),
                'date_fin_contrat' => $nouveauContrat->getDateFinContrat()->format('Y-m-d'),
                'statut' => $nouveauContrat->isStatut()
            ]
        ]);
    }

    //-------------------------------------------------------------------------------

    #[Route('/create-kine', name: 'create_kine', methods: ['POST'])]
    public function createKine(
        Request $request,
        ManagerRegistry $doctrine,
        EquipeRepository $equipeRepository,
        UserRepository $userRepository
    ): JsonResponse {
        $em = $doctrine->getManager();
        $data = json_decode($request->getContent(), true);

        // Récupérer l'unique équipe
        $equipe = $equipeRepository->findOneBy([]);
        if (!$equipe) {
            return new JsonResponse(['error' => 'Aucune équipe trouvée'], 404);
        }

        $email = $data['email'];
        $firstname = $data['firstname'];
        $lastname = $data['lastname'];
        $birthdate = new \DateTime($data['birthdate']);
        $phone_number = $data['phone_number'];
        $salaire = $data['salaire'];
        $dateAffectation = new \DateTime($data['date_affectation']);
        $dateFinContrat = isset($data['date_fin_contrat']) && !empty($data['date_fin_contrat'])
            ? new \DateTime($data['date_fin_contrat'])  
            : null;

        $existingUser = $userRepository->findOneBy(['email' => $email]);

        if ($existingUser) {
            return new JsonResponse(['error' => 'Cet email est déjà utilisé par un autre compte'], 400);
        }

        // Créer le kine
        $kine = new Kine();
        $kine->setEmail($email);
        $kine->setFirstname($firstname);
        $kine->setLastname($lastname);
        $kine->setBirthdate($birthdate);
        $kine->setPhoneNumber($phone_number);
        $kine->setEquipe($equipe);

        // Créer le contrat du joueur
        $contrat = new ContratKine();
        $contrat->setKine($kine);
        $contrat->setSalaire($salaire);
        $contrat->setDateAffectation($dateAffectation);
        $contrat->setDateFinContrat($dateFinContrat);
        $contrat->setStatut(true); // Actif par défaut

        // Ajouter le contrat au joueur
        $kine->addContrat($contrat);

        $equipe -> addKine($kine);

        // Sauvegarde en base de données
        $em->persist($kine);
        $em->persist($contrat);
        $em->persist($equipe);
        $em->flush();

        return $this->json([
            'message' => 'Joueur et contrat créés avec succès et affecté à l\'equipe ',
            'kine' => [
                'id' => $kine->getId(),
                'nom' => $kine->getFirstname() . ' ' . $kine->getLastname(),
                'equipe' => $equipe->getNom(),
                'contrat' => [
                    'salaire' => $contrat->getSalaire(),
                    'date_affectation' => $contrat->getDateAffectation()->format('Y-m-d'),
                    'date_fin_contrat' => $contrat->getDateFinContrat() ? $contrat->getDateFinContrat()->format('Y-m-d') : null
                ]
            ]
        ]);
    }

    //****************************** 

    #[Route('/update-kine/{id}', name: 'update_kine', methods: ['PUT', 'PATCH'])]
    public function updateKine(
        int $id,
        Request $request,
        UserPasswordHasherInterface $passwordHasher,
        ManagerRegistry $doctrine
    ): JsonResponse {
        $em = $doctrine->getManager();
        $data = json_decode($request->getContent(), true);

        // Récupérer le joueur par ID
        $kine = $doctrine->getRepository(Kine::class)->find($id);

        if (!$kine) {
            return $this->json(['message' => 'Kine non trouvé'], 404);
        }

        // Mettre à jour les champs fournis
        if (isset($data['phone_number']) && !empty($data['phone_number'])) {
            $kine->setPhoneNumber($data['phone_number']);
        }

        if (isset($data['email']) && !empty($data['email'])) {
           $kine->setEmail($data['email']);
        }

        // Sauvegarder les modifications
        $em->flush();

        return $this->json(['message' => 'Kine mis à jour avec succès']);
    }

    //****************************** 

    #[Route('/listkines', name: 'get_kines', methods: ['GET'])]
    public function getKines(KineRepository $kineRepository): JsonResponse
    {
        // Récupérer tous les kinésithérapeutes avec leurs contrats
        $kines = $kineRepository->findAll();

        // Structurer les données pour la réponse JSON
        $responseData = [];
        foreach ($kines as $kine) {
            $contratsData = [];
            foreach ($kine->getContrats() as $contrat) {
                $contratsData[] = [
                    'id' => $contrat->getId(),
                    'salaire' => $contrat->getSalaire(),
                    'date_affectation' => $contrat->getDateAffectation()->format('Y-m-d'),
                    'date_fin_contrat' => $contrat->getDateFinContrat() ? $contrat->getDateFinContrat()->format('Y-m-d') : null,
                    'statut' => $contrat->isStatut()
                ];
            }

            $responseData[] = [
                'id' => $kine->getId(),
                'email' => $kine->getEmail(),
                'firstname' => $kine->getFirstname(),
                'lastname' => $kine->getLastname(),
                'birthdate' => $kine->getBirthdate()->format('Y-m-d'),
                'phone_number' => $kine->getPhoneNumber(),
                'equipe' => $kine->getEquipe() ? $kine->getEquipe()->getNom() : null,
                'contrats' => $contratsData
            ];
        }

        return $this->json(['kines' => $responseData]);
    }

    //******************************

    #[Route('/create-president', name: 'create_president', methods: ['POST'])]
    public function createPresident(Request $request, UserPasswordHasherInterface $passwordHasher, ManagerRegistry $doctrine): JsonResponse
    {
        $em = $doctrine->getManager();
        $data = json_decode($request->getContent(), true);

        $email = $data['email'];
        $password = $data['password'];
        $firstname = $data['firstname'];
        $lastname = $data['lastname'];
        $birthdate = new \DateTime($data['birthdate']);
        $phone_number = $data['phone_number'];
        $salaire = $data['salaire'];
        $dateAffectation = new \DateTime($data['date_affectation']);
        $dateFinContrat = isset($data['date_fin_contrat']) && !empty($data['date_fin_contrat'])
            ? new \DateTime($data['date_fin_contrat'])  
            : null;

        // Récupérer l'équipe
        $equipe = $doctrine->getRepository(Equipe::class)->findOneBy([]);

        if (!$equipe) {
            return $this->json(['message' => 'Équipe non trouvée'], 404);
        }

        // Vérifier si l'équipe a déjà un président
        if ($equipe->getPresident() !== null) {
            return $this->json(['message' => 'Cette équipe a déjà un président'], 400);
        }

        // Créer l'utilisateur
        $user = new User();
        $user->setEmail($email);
        $user->setUsername($email);
        $hashedPassword = $passwordHasher->hashPassword($user, $password);
        $user->setPassword($hashedPassword);
        $user->setRoles(['ROLE_PRESIDENT']);

        // Créer le président
        $president = new President();
        $president->setFirstname($firstname);
        $president->setLastname($lastname);
        $president->setBirthdate($birthdate);
        $president->setPhoneNumber($phone_number);
        $president->setUser($user);  // Lier le président à l'utilisateur

        // Affecter le président à l'équipe
        $equipe->setPresident($president);  // Lier le président à l'équipe

        $contrat = new ContratPresident();
        $contrat->setPresident($president);
        $contrat->setSalaire($salaire);
        $contrat->setDateAffectation($dateAffectation);
        $contrat->setDateFinContrat($dateFinContrat);
        $contrat->setStatut(true);

        $president->addContrat($contrat);

        // Sauvegarder l'utilisateur, le président et l'équipe
        $em->persist($user);
        $em->persist($president);
        $em->persist($equipe);  // Persister l'équipe également pour enregistrer le changement
        $em->flush();

        return $this->json(['message' => 'Président et son contrat créé et affecté à l\'équipe avec succès']);
    }

    //******************************

    #[Route('/update-president/{id}', name: 'update_president', methods: ['PUT', 'PATCH'])]
    public function updatePresident(
        int $id,
        Request $request,
        UserPasswordHasherInterface $passwordHasher,
        ManagerRegistry $doctrine
    ): JsonResponse {
        $em = $doctrine->getManager();
        $data = json_decode($request->getContent(), true);

        // Récupérer le président par ID
        $president = $doctrine->getRepository(President::class)->find($id);

        if (!$president) {
            return $this->json(['message' => 'Président non trouvé'], 404);
        }

        // Mettre à jour les champs fournis
        if (isset($data['phone_number']) && !empty($data['phone_number'])) {
            $president->setPhoneNumber($data['phone_number']);
        }

        if (isset($data['date_affectation']) && !empty($data['date_affectation'])) {
            $president->setDateAffectation(new \DateTime($data['date_affectation']));
        }

        if (isset($data['date_fin_contrat']) && !empty($data['date_fin_contrat'])) {
            $president->setDateFinContrat(!empty($data['date_fin_contrat']) ? new \DateTime($data['date_fin_contrat']) : null);
        }

        // Mettre à jour l'email et/ou le mot de passe si fourni
        $user = $president->getUser();

        if (isset($data['email']) && !empty($data['email'])) {
            $user->setEmail($data['email']);
            $user->setUsername($data['email']);
        }

        if (isset($data['password']) && !empty($data['password'])) {
            $hashedPassword = $passwordHasher->hashPassword($user, $data['password']);
            $user->setPassword($hashedPassword);
        }

        // Sauvegarder les modifications
        $em->flush();

        return $this->json(['message' => 'Président mis à jour avec succès']);
    }

    //****************************** 

    #[Route('/listpresidents', name: 'listpresidents', methods: ['GET'])]
    public function listPresidents(ManagerRegistry $doctrine): JsonResponse
    {
        $presidents = $doctrine->getRepository(President::class)->findAll();
        
        $presidentData = [];
        foreach ($presidents as $president) {
            // Récupérer les contrats du président
            $contratsData = [];
            foreach ($president->getContrats() as $contrat) {
                $contratsData[] = [
                    'id' => $contrat->getId(),
                    'salaire' => $contrat->getSalaire(),
                    'date_affectation' => $contrat->getDateAffectation()->format('Y-m-d'),
                    'date_fin_contrat' => $contrat->getDateFinContrat() ? $contrat->getDateFinContrat()->format('Y-m-d') : null,
                    'statut' => $contrat->isStatut(),
                ];
            }

            // Ajouter les informations du président et ses contrats
            $presidentData[] = [
                'id' => $president->getId(),
                'firstname' => $president->getFirstname(),
                'lastname' => $president->getLastname(),
                'birthdate' => $president->getBirthdate()->format('Y-m-d'),
                'phone_number' => $president->getPhoneNumber(),
                'contrats' => $contratsData, // Ajout des contrats
            ];
        }
        
        return $this->json($presidentData);
    }

    //-------------------------------------------------------------------------------

    #[Route('/create-entraineur', name: 'create_entraineur', methods: ['POST'])]
    public function createEntraineur(Request $request, UserPasswordHasherInterface $passwordHasher, ManagerRegistry $doctrine): JsonResponse
    {
        $em = $doctrine->getManager();
        $data = json_decode($request->getContent(), true);

        $email = $data['email'];
        $password = $data['password'];
        $firstname = $data['firstname'];
        $lastname = $data['lastname'];
        $birthdate = new \DateTime($data['birthdate']);
        $specialite = $data['specialite'];
        $phone_number = $data['phone_number'];
        $salaire = $data['salaire'];
        $dateAffectation = new \DateTime($data['date_affectation']);
        $dateFinContrat = isset($data['date_fin_contrat']) && !empty($data['date_fin_contrat']) ? new \DateTime($data['date_fin_contrat']) : null;

        // Récupérer l'équipe unique
        $equipe = $doctrine->getRepository(Equipe::class)->findOneBy([]);
        if (!$equipe) {
            return $this->json(['message' => 'Aucune équipe trouvée'], JsonResponse::HTTP_NOT_FOUND);
        }

        // Créer l'utilisateur
        $user = new User();
        $user->setEmail($email);
        $user->setUsername($email);
        $hashedPassword = $passwordHasher->hashPassword($user, $password);
        $user->setPassword($hashedPassword);
        $user->setRoles(['ROLE_ENTRAINEUR']);

        // Créer l'entraîneur
        $entraineur = new Entraineur();
        $entraineur->setFirstname($firstname);
        $entraineur->setLastname($lastname);
        $entraineur->setBirthdate($birthdate);
        $entraineur->setSpecialite($specialite);
        $entraineur->setPhoneNumber($phone_number);
        $entraineur->setUser($user);  

        // Lier l'entraîneur à l'équipe
        $entraineur->setEquipe($equipe);

        // Ajouter l'entraîneur à la liste des entraîneurs de l'équipe
        $equipe->addEntraineur($entraineur);

        // Ajouter un contrat pour le entraineur
        $contrat = new ContratEntraineur();
        $contrat->setEntraineur($entraineur);
        $contrat->setSalaire($salaire);
        $contrat->setDateAffectation($dateAffectation);
        $contrat->setDateFinContrat($dateFinContrat);
        $contrat->setStatut(true);

        $entraineur->addContrat($contrat);

        // Sauvegarder l'utilisateur et l'entraîneur
        $em->persist($user);
        $em->persist($entraineur);
        $em->persist($equipe);
        $em->flush();

        return $this->json(['message' => 'Entraîneur et son contrat créés et affecté à l\'équipe avec succès']);
    }

    //****************************** 

    #[Route('/update-entraineur/{id}', name: 'update_entraineur', methods: ['PUT', 'PATCH'])]
    public function updateEntraineur(
        int $id,
        Request $request,
        UserPasswordHasherInterface $passwordHasher,
        ManagerRegistry $doctrine
    ): JsonResponse {
        $em = $doctrine->getManager();
        $data = json_decode($request->getContent(), true);

        // Récupérer l'entraîneur par ID
        $entraineur = $doctrine->getRepository(Entraineur::class)->find($id);

        if (!$entraineur) {
            return $this->json(['message' => 'Entraîneur non trouvé'], 404);
        }

        // Mettre à jour les champs fournis
        if (isset($data['phone_number']) && !empty($data['phone_number'])) {
            $entraineur->setPhoneNumber($data['phone_number']);
        }

        // Mettre à jour l'email et/ou le mot de passe si fourni
        $user = $entraineur->getUser();

        if (isset($data['email']) && !empty($data['email'])) {
            $user->setEmail($data['email']);
            $user->setUsername($data['email']);
        }

        if (isset($data['password']) && !empty($data['password'])) {
            $hashedPassword = $passwordHasher->hashPassword($user, $data['password']);
            $user->setPassword($hashedPassword);
        }

        // Sauvegarder les modifications
        $em->flush();

        return $this->json(['message' => 'Entraîneur mis à jour avec succès']);
    }

    //****************************** 

    #[Route('/desactiver-contrat-entraineur/{entraineur_id}', name: 'desactiver_contrat_entraineur', methods: ['PATCH'])]
    public function desactiverContratEntraineur(
        int $entraineur_id,
        ManagerRegistry $doctrine
    ): JsonResponse {
        $em = $doctrine->getManager();

        // Récupérer le entraineur par ID
        $entraineur = $doctrine->getRepository(Entraineur::class)->find($entraineur_id);
        if (!$entraineur) {
            return $this->json(['message' => 'Entraineur non trouvé'], 404);
        }

        // Récupérer le contrat actuel
        $contrat = $entraineur->getContrats()->filter(function($contrat) {
            return $contrat->isStatut() === true; // Vérifie que le statut est actif
        })->first();
        
        if (!$contrat) {
            return $this->json(['message' => 'Le entraineur n\'a pas de contrat actif'], 404);
        }

        // Désactiver le contrat
        $contrat->setStatut(false);

        $entraineur->setEquipe(null);

        // Sauvegarder les modifications
        $em->flush();

        return $this->json(['message' => 'Contrat du entraineur désactivé avec succès']);
    }

    //******************************

    #[Route('/listentraineurs', name: 'listentraineurs', methods: ['GET'])]
    public function listEntraineurs(ManagerRegistry $doctrine): JsonResponse
    {
        $entraineurs = $doctrine->getRepository(Entraineur::class)->findAll();
        
        $entraineurData = [];
        foreach ($entraineurs as $entraineur) {
            // Récupérer les contrats de l'entraîneur
            $contratsData = [];
            foreach ($entraineur->getContrats() as $contrat) {
                $contratsData[] = [
                    'id' => $contrat->getId(),
                    'salaire' => $contrat->getSalaire(),
                    'date_affectation' => $contrat->getDateAffectation()->format('Y-m-d'),
                    'date_fin_contrat' => $contrat->getDateFinContrat() ? $contrat->getDateFinContrat()->format('Y-m-d') : null,
                    'statut' => $contrat->isStatut(),
                ];
            }

            // Ajouter les informations de l'entraîneur et ses contrats
            $entraineurData[] = [
                'id' => $entraineur->getId(),
                'firstname' => $entraineur->getFirstname(),
                'lastname' => $entraineur->getLastname(),
                'birthdate' => $entraineur->getBirthdate()->format('Y-m-d'),
                'specialite' => $entraineur->getSpecialite(),
                'phone_number' => $entraineur->getPhoneNumber(),
                'contrats' => $contratsData, // Ajout des contrats
            ];
        }
        
        return $this->json($entraineurData);
    }

    //-------------------------------------------------------------------------------

    #[Route('/create-joueur', name: 'create_joueur', methods: ['POST'])]
    public function createJoueur(
        Request $request,
        UserPasswordHasherInterface $passwordHasher,
        ManagerRegistry $doctrine,
        EquipeRepository $equipeRepository
    ): JsonResponse {
        $em = $doctrine->getManager();
        $data = json_decode($request->getContent(), true);

        // Récupérer l'unique équipe
        $equipe = $equipeRepository->findOneBy([]);
        if (!$equipe) {
            return new JsonResponse(['error' => 'Aucune équipe trouvée'], 404);
        }

        $email = $data['email'];
        $password = $data['password'];
        $firstname = $data['firstname'];
        $lastname = $data['lastname'];
        $birthdate = new \DateTime($data['birthdate']);
        $position = $data['position'];
        $phone_number = $data['phone_number'];
        $numero_maillot = $data['numero_maillot'];
        $nb_carton_rouge = $data['nb_carton_rouge'];
        $nb_carton_jaune = $data['nb_carton_jaune'];
        $salaire = $data['salaire'];
        $dateAffectation = new \DateTime($data['date_affectation']);
        $dateFinContrat = isset($data['date_fin_contrat']) && !empty($data['date_fin_contrat'])
            ? new \DateTime($data['date_fin_contrat'])  
            : null;

        // Créer l'utilisateur
        $user = new User();
        $user->setEmail($email);
        $user->setUsername($email);
        $hashedPassword = $passwordHasher->hashPassword($user, $password);
        $user->setPassword($hashedPassword);
        $user->setRoles(['ROLE_JOUEUR']);

        // Créer le joueur
        $joueur = new Joueur();
        $joueur->setFirstname($firstname);
        $joueur->setLastname($lastname);
        $joueur->setBirthdate($birthdate);
        $joueur->setPosition($position);
        $joueur->setPhoneNumber($phone_number);
        $joueur->setNumeroMaillot($numero_maillot);
        $joueur->setNbCartonRouge($nb_carton_rouge);
        $joueur->setNbCartonJaune($nb_carton_jaune);
        $joueur->setUser($user);
        $joueur->setEquipe($equipe);

        // Lier l'utilisateur au joueur
        $user->setJoueur($joueur);

        // Créer le contrat du joueur
        $contrat = new ContratJoueur();
        $contrat->setJoueur($joueur);
        $contrat->setSalaire($salaire);
        $contrat->setDateAffectation($dateAffectation);
        $contrat->setDateFinContrat($dateFinContrat);
        $contrat->setStatut(true); // Actif par défaut

        // Ajouter le contrat au joueur
        $joueur->addContrat($contrat);

        // Sauvegarde en base de données
        $em->persist($user);
        $em->persist($joueur);
        $em->persist($contrat);
        $em->flush();

        return $this->json([
            'message' => 'Joueur et contrat créés avec succès',
            'joueur' => [
                'id' => $joueur->getId(),
                'nom' => $joueur->getFirstname() . ' ' . $joueur->getLastname(),
                'equipe' => $equipe->getNom(),
                'contrat' => [
                    'salaire' => $contrat->getSalaire(),
                    'date_affectation' => $contrat->getDateAffectation()->format('Y-m-d'),
                    'date_fin_contrat' => $contrat->getDateFinContrat() ? $contrat->getDateFinContrat()->format('Y-m-d') : null
                ]
            ]
        ]);
    }

    //****************************** 

    #[Route('/update-joueur/{id}', name: 'update_joueur', methods: ['PUT', 'PATCH'])]
    public function updateJoueur(
        int $id,
        Request $request,
        UserPasswordHasherInterface $passwordHasher,
        ManagerRegistry $doctrine
    ): JsonResponse {
        $em = $doctrine->getManager();
        $data = json_decode($request->getContent(), true);

        // Récupérer le joueur par ID
        $joueur = $doctrine->getRepository(Joueur::class)->find($id);

        if (!$joueur) {
            return $this->json(['message' => 'Joueur non trouvé'], 404);
        }

        // Mettre à jour les champs fournis


        if (isset($data['position']) && !empty($data['position'])) {
            $joueur->setPosition($data['position']);
        }

        if (isset($data['phone_number']) && !empty($data['phone_number'])) {
            $joueur->setPhoneNumber($data['phone_number']);
        }

        if (isset($data['numero_maillot']) && !empty($data['numero_maillot'])) {
            $joueur->setNumeroMaillot($data['numero_maillot']);
        }

        if (isset($data['nb_carton_rouge']) && !empty($data['nb_carton_rouge'])) {
            $joueur->setNbCartonRouge($data['nb_carton_rouge']);
        }

        if (isset($data['nb_carton_jaune']) && !empty($data['nb_carton_jaune'])) {
            $joueur->setNbCartonJaune($data['nb_carton_jaune']);
        }

        // Mettre à jour l'email et/ou le mot de passe si fourni
        $user = $joueur->getUser();

        if (isset($data['email']) && !empty($data['email'])) {
            $user->setEmail($data['email']);
            $user->setUsername($data['email']);
        }

        if (isset($data['password']) && !empty($data['password'])) {
            $hashedPassword = $passwordHasher->hashPassword($user, $data['password']);
            $user->setPassword($hashedPassword);
        }

        // Sauvegarder les modifications
        $em->flush();

        return $this->json(['message' => 'Joueur mis à jour avec succès']);
    }

    //****************************** 

    #[Route('/desactiver-contrat-joueur/{joueur_id}', name: 'desactiver_contrat_joueur', methods: ['PATCH'])]
    public function desactiverContrat(
        int $joueur_id,
        ManagerRegistry $doctrine
    ): JsonResponse {
        $em = $doctrine->getManager();

        // Récupérer le joueur par ID
        $joueur = $doctrine->getRepository(Joueur::class)->find($joueur_id);
        if (!$joueur) {
            return $this->json(['message' => 'Joueur non trouvé'], 404);
        }

        // Récupérer le contrat actuel
        $contrat = $joueur->getContrats()->filter(function($contrat) {
            return $contrat->isStatut() === true; // Vérifie que le statut est actif
        })->first();
        
        if (!$contrat) {
            return $this->json(['message' => 'Le joueur n\'a pas de contrat actif'], 404);
        }

        // Désactiver le contrat
        $contrat->setStatut(false);

        $joueur->setEquipe(null);

        // Sauvegarder les modifications
        $em->flush();

        return $this->json(['message' => 'Contrat du joueur désactivé avec succès']);
    }

    //****************************** 

    #[Route('/renouvellement-contrat-joueur/{joueur_id}', name: 'renouvellement_contrat-joueur', methods: ['POST'])]
    public function createContratJoueur(
        int $joueur_id,
        Request $request,
        ManagerRegistry $doctrine
    ): JsonResponse {
        $em = $doctrine->getManager();
        $data = json_decode($request->getContent(), true);

        // Récupérer le joueur par ID
        $joueur = $doctrine->getRepository(Joueur::class)->find($joueur_id);
        if (!$joueur) {
            return $this->json(['message' => 'Joueur non trouvé'], 404);
        }

        // Récupérer l'ancien contrat actif
        $ancienContrat = $joueur->getContrats()->filter(function($contrat) {
            return $contrat->isStatut() === true; // Seules les contrats actifs sont considérés
        })->first();

        // Vérification si un contrat actif existe
        if ($ancienContrat) {
            // Vérifier si la date actuelle est après la date de fin du contrat
            $currentDate = new \DateTime();
            $dateFinContrat = $ancienContrat->getDateFinContrat();

            if ($currentDate <= $dateFinContrat) {
                return $this->json([
                    'message' => 'Le contrat actuel est encore valide, vous ne pouvez pas renouveler tant qu\'il est en cours.'
                ], 400);
            }
        }

        // Créer un nouveau contrat
        $nouveauContrat = new ContratJoueur();
        $nouveauContrat->setSalaire($data['salaire']);
        $nouveauContrat->setDateAffectation(new \DateTime($data['date_affectation']));
        $nouveauContrat->setDateFinContrat(new \DateTime($data['date_fin_contrat']));
        $nouveauContrat->setStatut(true);  // Le statut est actif par défaut
        $joueur->addContrat($nouveauContrat);

        // Sauvegarder le nouveau contrat
        $em->persist($nouveauContrat);
        $em->flush();

        return $this->json([
            'message' => 'Nouveau contrat créé avec succès pour le joueur',
            'contrat' => [
                'salaire' => $nouveauContrat->getSalaire(),
                'date_affectation' => $nouveauContrat->getDateAffectation()->format('Y-m-d'),
                'date_fin_contrat' => $nouveauContrat->getDateFinContrat()->format('Y-m-d'),
                'statut' => $nouveauContrat->isStatut()
            ]
        ]);
    }

    // ********************************

    #[Route('/listjoueurs', name: 'listjoueurs', methods: ['GET'])]
    public function listJoueurs(ManagerRegistry $doctrine): JsonResponse
    {
        $joueurs = $doctrine->getRepository(Joueur::class)->findAll();
        
        $joueurData = [];
        foreach ($joueurs as $joueur) {
            // Récupérer les contrats du joueur
            $contratsData = [];
            foreach ($joueur->getContrats() as $contrat) {
                $contratsData[] = [
                    'id' => $contrat->getId(),
                    'salaire' => $contrat->getSalaire(),
                    'date_affectation' => $contrat->getDateAffectation()->format('Y-m-d'),
                    'date_fin_contrat' => $contrat->getDateFinContrat() ? $contrat->getDateFinContrat()->format('Y-m-d') : null,
                    'statut' => $contrat->isStatut(),
                ];
            }

            // Ajouter les informations du joueur et ses contrats
            $joueurData[] = [
                'id' => $joueur->getId(),
                'firstname' => $joueur->getFirstname(),
                'lastname' => $joueur->getLastname(),
                'birthdate' => $joueur->getBirthdate()->format('Y-m-d'),
                'position' => $joueur->getPosition(),
                'phone_number' => $joueur->getPhoneNumber(),
                'numero_maillot' => $joueur->getNumeroMaillot(),
                'nb_carton_rouge' => $joueur->getNbCartonRouge(),
                'nb_carton_jaune' => $joueur->getNbCartonJaune(),
                'contrats' => $contratsData, // Ajout des contrats
            ];
        }
        
        return $this->json($joueurData);
    }

    //-------------------------------------------------------------------------------

    #[Route('/create-photographe', name: 'create_photographe', methods: ['POST'])]
    public function createPhotographe(Request $request, UserPasswordHasherInterface $passwordHasher, ManagerRegistry $doctrine): JsonResponse
    {
        $em = $doctrine->getManager();
        $data = json_decode($request->getContent(), true);

        $email = $data['email'];
        $password = $data['password'];
        $firstname = $data['firstname'];
        $lastname = $data['lastname'];
        $birthdate = new \DateTime($data['birthdate']);
        $phone_number = $data['phone_number'];
        $salaire = $data['salaire'];
        $dateAffectation = new \DateTime($data['date_affectation']);
        $dateFinContrat = isset($data['date_fin_contrat']) && !empty($data['date_fin_contrat']) ? new \DateTime($data['date_fin_contrat']) : null;

        // Récupérer l'équipe unique
        $equipe = $doctrine->getRepository(Equipe::class)->findOneBy([]);
        if (!$equipe) {
            return $this->json(['message' => 'Aucune équipe trouvée'], JsonResponse::HTTP_NOT_FOUND);
        }

        // Créer l'utilisateur
        $user = new User();
        $user->setEmail($email);
        $user->setUsername($email);
        $hashedPassword = $passwordHasher->hashPassword($user, $password);
        $user->setPassword($hashedPassword);
        $user->setRoles(['ROLE_PHOTOGRAPHE']);

        // Créer le photographe
        $photographe = new Photographe();
        $photographe->setFirstname($firstname);
        $photographe->setLastname($lastname);
        $photographe->setBirthdate($birthdate);
        $photographe->setPhoneNumber($phone_number);
        $photographe->setUser($user);

        // Lier le photographe à l'équipe
        $photographe->setEquipe($equipe);

        // Ajouter le photographe à la liste des photographes de l'équipe
        $equipe->addPhotographe($photographe);

        // Ajouter un contrat pour le photographe
        $contrat = new ContratPhotographe();
        $contrat->setPhotographe($photographe);
        $contrat->setSalaire($salaire);
        $contrat->setDateAffectation($dateAffectation);
        $contrat->setDateFinContrat($dateFinContrat);
        $contrat->setStatut(true);

        $photographe->addContrat($contrat);

        // Sauvegarder l'utilisateur et le photographe
        $em->persist($user);
        $em->persist($photographe);
        $em->persist($equipe);
        $em->flush();

        return $this->json(['message' => 'Photographe et son contrat créés et affecté à l\'équipe avec succès']);
    }

    //******************************

    #[Route('/update-photographe/{id}', name: 'update_photographe', methods: ['PUT', 'PATCH'])]
    public function updatePhotographe(
        int $id,
        Request $request,
        UserPasswordHasherInterface $passwordHasher,
        ManagerRegistry $doctrine
    ): JsonResponse {
        $em = $doctrine->getManager();
        $data = json_decode($request->getContent(), true);

        // Récupérer le photographe par ID
        $photographe = $doctrine->getRepository(Photographe::class)->find($id);

        if (!$photographe) {
            return $this->json(['message' => 'Photographe non trouvé'], 404);
        }

        // Mettre à jour les champs fournis

        if (isset($data['phone_number']) && !empty($data['phone_number'])) {
            $photographe->setPhoneNumber($data['phone_number']);
        }

        // Mettre à jour l'email et/ou le mot de passe si fourni
        $user = $photographe->getUser();

        if (isset($data['email']) && !empty($data['email'])) {
            $user->setEmail($data['email']);
            $user->setUsername($data['email']);
        }

        if (isset($data['password']) && !empty($data['password'])) {
            $hashedPassword = $passwordHasher->hashPassword($user, $data['password']);
            $user->setPassword($hashedPassword);
        }

        // Sauvegarder les modifications
        $em->flush();

        return $this->json(['message' => 'Photographe mis à jour avec succès']);
    }
    
    //****************************** 

    #[Route('/desactiver-contrat-photographe/{photographe_id}', name: 'desactiver_contrat_photographe', methods: ['PATCH'])]
    public function desactiverContratPhotographe(
        int $photographe_id,
        ManagerRegistry $doctrine
    ): JsonResponse {
        $em = $doctrine->getManager();

        // Récupérer le photographe par ID
        $photographe = $doctrine->getRepository(Photographe::class)->find($photographe_id);
        if (!$photographe) {
            return $this->json(['message' => 'Photographe non trouvé'], 404);
        }

        // Récupérer le contrat actuel
        $contrat = $photographe->getContrats()->filter(function($contrat) {
            return $contrat->isStatut() === true; // Vérifie que le statut est actif
        })->first();
        
        if (!$contrat) {
            return $this->json(['message' => 'Le Photographe n\'a pas de contrat actif'], 404);
        }

        // Désactiver le contrat
        $contrat->setStatut(false);

        $photographe->setEquipe(null);

        // Sauvegarder les modifications
        $em->flush();

        return $this->json(['message' => 'Contrat du photographe désactivé avec succès']);
    }

    //****************************** 

    #[Route('/renouvellement-contrat-photographe/{photographe_id}', name: 'renouvellement_contrat_photographe', methods: ['POST'])]
    public function createContratPhotographe(
        int $photographe_id,
        Request $request,
        ManagerRegistry $doctrine
    ): JsonResponse {
        $em = $doctrine->getManager();
        $data = json_decode($request->getContent(), true);

        // Récupérer le joueur par ID
        $photographe = $doctrine->getRepository(Photographe::class)->find($photographe_id);
        if (!$photographe) {
            return $this->json(['message' => 'Photographe non trouvé'], 404);
        }

        // Récupérer l'ancien contrat actif
        $ancienContrat = $photographe->getContrats()->filter(function($contrat) {
            return $contrat->isStatut() === true; // Seules les contrats actifs sont considérés
        })->first();

        // Vérification si un contrat actif existe
        if ($ancienContrat) {
            // Vérifier si la date actuelle est après la date de fin du contrat
            $currentDate = new \DateTime();
            $dateFinContrat = $ancienContrat->getDateFinContrat();

            if ($currentDate <= $dateFinContrat) {
                return $this->json([
                    'message' => 'Le contrat actuel est encore valide, vous ne pouvez pas renouveler tant qu\'il est en cours.'
                ], 400);
            }
        }

        // Créer un nouveau contrat
        $nouveauContrat = new ContratPhotographe();
        $nouveauContrat->setSalaire($data['salaire']);
        $nouveauContrat->setDateAffectation(new \DateTime($data['date_affectation']));
        $nouveauContrat->setDateFinContrat(new \DateTime($data['date_fin_contrat']));
        $nouveauContrat->setStatut(true);  // Le statut est actif par défaut
        $photographe->addContrat($nouveauContrat);

        // Sauvegarder le nouveau contrat
        $em->persist($nouveauContrat);
        $em->flush();

        return $this->json([
            'message' => 'Nouveau contrat créé avec succès pour le photographe',
            'contrat' => [
                'salaire' => $nouveauContrat->getSalaire(),
                'date_affectation' => $nouveauContrat->getDateAffectation()->format('Y-m-d'),
                'date_fin_contrat' => $nouveauContrat->getDateFinContrat()->format('Y-m-d'),
                'statut' => $nouveauContrat->isStatut()
            ]
        ]);
    }

    // ********************************

    #[Route('/listphotographes', name: 'listphotographes', methods: ['GET'])]
    public function listPhotographes(ManagerRegistry $doctrine): JsonResponse
    {
        $photographes = $doctrine->getRepository(Photographe::class)->findAll();
        
        $photographeData = [];
        foreach ($photographes as $photographe) {
            // Récupérer les contrats du photographe
            $contratsData = [];
            foreach ($photographe->getContrats() as $contrat) {
                $contratsData[] = [
                    'id' => $contrat->getId(),
                    'salaire' => $contrat->getSalaire(),
                    'date_affectation' => $contrat->getDateAffectation()->format('Y-m-d'),
                    'date_fin_contrat' => $contrat->getDateFinContrat() ? $contrat->getDateFinContrat()->format('Y-m-d') : null,
                    'statut' => $contrat->isStatut(),
                ];
            }

            // Ajouter les informations du photographe et ses contrats
            $photographeData[] = [
                'id' => $photographe->getId(),
                'firstname' => $photographe->getFirstname(),
                'lastname' => $photographe->getLastname(),
                'birthdate' => $photographe->getBirthdate()->format('Y-m-d'),
                'phone_number' => $photographe->getPhoneNumber(),
                'contrats' => $contratsData, // Ajout des contrats
            ];
        }
        
        return $this->json($photographeData);
    }

    //******************************

    #[Route('/create-talent', name: 'create_talent', methods: ['POST'])]
    public function createTalent(Request $request, ManagerRegistry $doctrine): JsonResponse
    {
        $em = $doctrine->getManager();
        $data = json_decode($request->getContent(), true);

        $firstname = $data['firstname'];
        $lastname = $data['lastname'];
        $email = $data['email'];
        $birthdate = new \DateTime($data['birthdate']);
        $phone_number = $data['phone_number'];
        $position = $data['position'];

        // Créer le talent
        $talent = new TalentDataBase();
        $talent->setFirstname($firstname);
        $talent->setLastname($lastname);
        $talent->setEmail($email);
        $talent->setBirthdate($birthdate);
        $talent->setPhoneNumber($phone_number);
        $talent->setPosition($position);

        // Sauvegarder le talent
        $em->persist($talent);
        $em->flush();

        return $this->json(['message' => 'Joueur talent créé avec succès']);
    }

    //******************************

    #[Route('/update-talent/{id}', name: 'update_talent', methods: ['PUT'])]
    public function updateTalent(int $id, Request $request, ManagerRegistry $doctrine, TalentDataBaseRepository $talentRepo): JsonResponse
    {
        $em = $doctrine->getManager();
        $talent = $talentRepo->find($id);

        // Vérifier si le talent existe
        if (!$talent) {
            return $this->json(['message' => 'Talent non trouvé'], JsonResponse::HTTP_NOT_FOUND);
        }

        $data = json_decode($request->getContent(), true);

        // Vérifier et mettre à jour chaque champ s'il est présent dans la requête
        
        if (isset($data['email']) && !empty($data['email'])) {
            $talent->setEmail($data['email']);
        }
        if (isset($data['phone_number']) && !empty($data['phone_number'])) {
            $talent->setPhoneNumber($data['phone_number']);
        }
        
        // Sauvegarde des modifications
        $em->flush();

        return $this->json(['message' => 'Talent mis à jour avec succès']);
    }

    //******************************

    #[Route('/listtalents', name: 'get_talents', methods: ['GET'])]
    public function getTalents(TalentDataBaseRepository $talentRepo): JsonResponse
    {
        // Récupérer tous les talents depuis la base de données
        $talents = $talentRepo->findAll();

        // Formatter les talents en tableau associatif
        $talentsArray = array_map(function ($talent) {
            return [
                'id' => $talent->getId(),
                'firstname' => $talent->getFirstname(),
                'lastname' => $talent->getLastname(),
                'email' => $talent->getEmail(),
                'birthdate' => $talent->getBirthdate()->format('Y-m-d'),
                'phone_number' => $talent->getPhoneNumber(),
                'position' => $talent->getPosition(),
            ];
        }, $talents);

        return $this->json($talentsArray);
    }

    //******************************

    #[Route('/acheter-joueur/{id}', name: 'acheter_joueur', methods: ['POST'])]
    public function acheterJoueur(
        int $id,
        Request $request,
        TalentDataBaseRepository $talentRepo,
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $passwordHasher,
        EquipeRepository $equipeRepository
    ): JsonResponse {

        $data = json_decode($request->getContent(), true);

        $equipe = $equipeRepository->findOneBy([]);
        if (!$equipe) {
            return new JsonResponse(['error' => 'Aucune équipe trouvée'], 404);
        }

        // Récupérer le joueur depuis la table TalentDataBase
        $talent = $talentRepo->find($id);
        if (!$talent) {
            return new JsonResponse(['message' => 'Joueur non trouvé'], 404);
        }

        $email = $data['email'];
        $password = $data['password'];
        $dateAffectation = new \DateTime($data['date_affectation']);
        $dateFinContrat = isset($data['date_fin_contrat']) && !empty($data['date_fin_contrat']) ? new \DateTime($data['date_fin_contrat']) : null;
        $numero_maillot = $data['numero_maillot'];
        $salaire = $data['salaire'];
        
        // Créer un nouvel utilisateur (User)
        $user = new User();
        $user->setEmail($email);
        $user->setUsername($email);
        $hashedPassword = $passwordHasher->hashPassword($user, $password);
        $user->setPassword($hashedPassword);
        $user->setRoles(['ROLE_JOUEUR']);

        // Créer un joueur (Joueur)
        $joueur = new Joueur();
        $joueur->setFirstname($talent->getFirstname());
        $joueur->setLastname($talent->getLastname());
        $joueur->setPhoneNumber($talent->getPhoneNumber());
        $joueur->setBirthdate($talent->getBirthdate());
        $joueur->setPosition($talent->getPosition());
        $joueur->setNumeroMaillot($numero_maillot);
        $joueur->setNbCartonRouge(null);
        $joueur->setNbCartonJaune(null);
        $joueur->setUser($user);
        $joueur->setEquipe($equipe);

        $user->setJoueur($joueur);

        // Créer le contrat du joueur
        $contrat = new ContratJoueur();
        $contrat->setJoueur($joueur);
        $contrat->setSalaire($salaire);
        $contrat->setDateAffectation($dateAffectation);
        $contrat->setDateFinContrat($dateFinContrat);
        $contrat->setStatut(true);

        // Ajouter le contrat au joueur
        $joueur->addContrat($contrat);

        // Sauvegarder dans la base de données
        $entityManager->persist($user);
        $entityManager->persist($joueur);
        $entityManager->persist($contrat);
        $entityManager->flush();

        // Supprimer le joueur de TalentDataBase
        $entityManager->remove($talent);
        $entityManager->flush();

        return new JsonResponse([
            'message' => 'Joueur acheté avec succès',
        ], 201);
    }
    //*****************************

    #[Route('/create-task', name: 'create_task', methods: ['POST'])]
    public function createTask(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

           // Vérification des champs obligatoires
    if (!isset($data['nom']) || empty($data['nom'])){
        return $this->json(['error' => 'Le nom de task est requis'], 400);
    }

    if (!isset($data['type']) || empty($data['type'])){
        return $this->json(['error' => 'Le type de task est requis'], 400);
    }
    if (!isset($data['duree']) || empty($data['duree'])){
        return $this->json(['error' => 'Le duree de task est requis'], 400);
    }

    $task = new Task();
    $task->setNom($data['nom']);
    $task->setType($data['type']);
    //$task->setDuree($data['duree']);
    

    if (!empty($data['duree'])) {
    try {
        // Vérification du format HH:MM:SS
        $timeParts = explode(':', $data['duree']);
        if (count($timeParts) !== 3) {
            return new JsonResponse(['error' => 'Format de durée invalide, attendu HH:MM:SS'], 400);
        }

        // Création d'un objet DateTime uniquement avec l'heure
        $date = new \DateTime();
        $date->setTime((int) $timeParts[0], (int) $timeParts[1], (int) $timeParts[2]);

        $task->setDuree($date);
    } catch (\Exception $e) {
        return new JsonResponse(['error' => 'Format de durée invalide'], 400);
    }
    }
    // Vérification et conversion de la durée
    if (!empty($data['duree'])) {
        try {
            // Vérification du format HH:MM:SS
            $timeParts = explode(':', $data['duree']);
            if (count($timeParts) !== 3) {
                return new JsonResponse(['error' => 'Format de durée invalide, attendu HH:MM:SS'], 400);
            }
    
            // Création d'un objet DateTime uniquement avec l'heure
            $date = new \DateTime();
            $date->setTime((int) $timeParts[0], (int) $timeParts[1], (int) $timeParts[2]);
    
            $task->setDuree($date);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'Format de durée invalide'], 400);
        }
    }

    // Enregistrement dans la base de données
    $entityManager->persist($task);
    $entityManager->flush();

    return new JsonResponse([
        'message' => 'Tâche créée avec succès',
        'task' => [
            'id' => $task->getId(),
            'nom' => $task->getNom(),
            'type' => $task->getType(),
            'duree' => $task->getDuree()?->format('H:i:s'),
        ]
    ], 201);
    }


    #[Route('/update-task/{id}', name: 'update_task', methods: ['PUT'])]
    public function updateTask(int $id, Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
    $task = $entityManager->getRepository(Task::class)->find($id);

    if (!$task) {
        return new JsonResponse(['error' => 'Tâche non trouvée'], 404);
    }

    $data = json_decode($request->getContent(), true);

    if (!empty($data['nom'])) {
        $task->setNom($data['nom']);
    }

    if (!empty($data['type'])) {
        $task->setType($data['type']);
    }

    if (!empty($data['duree'])) {
        try {
            $task->setDuree(new \DateTime($data['duree']));
        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'Format de durée invalide'], 400);
        }
    }

    $entityManager->flush();

    return new JsonResponse([
        'message' => 'Tâche mise à jour avec succès',
        'task' => [
            'id' => $task->getId(),
            'nom' => $task->getNom(),
            'type' => $task->getType(),
            'duree' => $task->getDuree()?->format('H:i:s'),
        ]
    ]);
    }


    #[Route('/list-tasks', name: 'list_tasks', methods: ['GET'])]
    public function listTasks(EntityManagerInterface $entityManager): JsonResponse
    {
        $tasks = $entityManager->getRepository(Task::class)->findAll();

        $tasksArray = [];
        foreach ($tasks as $task) {
            $tasksArray[] = [
                'id' => $task->getId(),
                'nom' => $task->getNom(),
                'type' => $task->getType(),
                'duree' => $task->getDuree()?->format('H:i:s'),
            ];
        }
    
        return new JsonResponse($tasksArray);
    }

    #[Route('/delete-task/{id}', name: 'delete_task', methods: ['DELETE'])]
    public function deleteTask(int $id, ManagerRegistry $doctrine): JsonResponse
    {
        $em = $doctrine->getManager();
        $task = $em->getRepository(Task::class)->find($id);
    
        if (!$task) {
            return $this->json(['error' => 'Task non trouve'], 404);
        }
    
        // Suppression de la task dans toutes les TrainingSessions
        $trainingSessions = $em->getRepository(TrainingSession::class)->findAll();
        foreach ($trainingSessions as $session) {
            $tasks = $session->getTasks();
            if (in_array($id, $tasks)) {
                $tasks = array_filter($tasks, fn($taskId) => $taskId !== $id);
                $session->setTasks(array_values($tasks)); // Réindexation du tableau
            }
        }
    
        $em->remove($task);
        $em->flush();
    
        return $this->json(['message' => 'Task supprimé avec success']);
    }
    //****************************** 
    #[Route('/create-trainingsession', name: 'create_trainingsession', methods: ['POST'])]
    public function createTrainingsession(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
    
        // Liste des champs requis
        $champsRequis = ['date', 'time', 'joueurs', 'tasks'];
        foreach ($champsRequis as $champ) {
            if (empty($data[$champ])) {
                return $this->json(['error' => "Le champ '$champ' est requis"], 400);
            }
        }
    
        try {
            $date = new \DateTime($data['date']);
            $time = new \DateTime($data['time']);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'Format de date ou d\'heure invalide'], 400);
        }
    
        // Vérification des joueurs et des tâches
        $joueurs = $entityManager->getRepository(Joueur::class)->findBy(['id' => $data['joueurs']]);
        $tasks = $entityManager->getRepository(Task::class)->findBy(['id' => $data['tasks']]);
    
        if (count($joueurs) !== count($data['joueurs'])) {
            return $this->json(['error' => 'Un ou plusieurs joueurs n\'existent pas'], 400);
        }
    
        if (count($tasks) !== count($data['tasks'])) {
            return $this->json(['error' => 'Une ou plusieurs tâches n\'existent pas'], 400);
        }
    
        // Création et enregistrement de la session d'entraînement
        $trainingsession = new Trainingsession();
        $trainingsession->setDate($date);
        $trainingsession->setTime($time);
        $trainingsession->setJoueurs($joueurs);
        $trainingsession->setTasks($tasks);
    
        $entityManager->persist($trainingsession);
        $entityManager->flush();
    
        return new JsonResponse([
            'message' => 'Session d\'entraînement créée avec succès',
            'trainingsession' => [
                'id' => $trainingsession->getId(),
                'date' => $trainingsession->getDate()->format('Y-m-d'),
                'time' => $trainingsession->getTime()->format('H:i:s'),
            ]
        ], 201);
    }
    #[Route('/update-trainingsession/{id}', name: 'update_trainingsession', methods: ['PUT'])]
    public function updateTrainingSession(int $id, Request $request, ManagerRegistry $doctrine): JsonResponse
    {
        $em = $doctrine->getManager();
        $trainingSession = $em->getRepository(TrainingSession::class)->find($id);

        if (!$trainingSession) {
            return $this->json(['error' => 'Training session n\'est pas trouvé'], 404);
        }

        $data = json_decode($request->getContent(), true);

        // Mise à jour de la date et de l'heure
        if (isset($data['date']) && !empty($data['date']) ) {
            $trainingSession->setDate(new \DateTime($data['date']));
        }
        if (isset($data['time']) && !empty($data['time'])) {
            $trainingSession->setTime(new \DateTime($data['time']));
        }

        // Vérification et mise à jour des joueurs
        if (isset($data['joueurs']) && !empty($data['joueurs'])) {
            $joueursExistants = $em->getRepository(Joueur::class)->findBy(['id' => $data['joueurs']]);
            $joueursIdsExistants = array_map(fn($joueur) => $joueur->getId(), $joueursExistants);

            if (count($joueursIdsExistants) !== count($data['joueurs'])) {
                return $this->json(['error' => 'Un ou plusieurs ID joueurs non valides'], 400);
            }

            $trainingSession->setJoueurs($joueursIdsExistants);
        }

        // Vérification et mise à jour des tasks
        if (isset($data['tasks']) && !empty($data['tasks'])) {
            $tasksExistantes = $em->getRepository(Task::class)->findBy(['id' => $data['tasks']]);
            $tasksIdsExistants = array_map(fn($task) => $task->getId(), $tasksExistantes);

            if (count($tasksIdsExistants) !== count($data['tasks'])) {
                return $this->json(['error' => 'Un ou plusieurs ID tasks non valides'], 400);
            }

            $trainingSession->setTasks($tasksIdsExistants);
        }

        $em->flush();

        return $this->json(['message' => 'Training session mis à jour avec succès']);
    }
       
    #[Route('/delete-trainingsession/{id}', name: 'delete_training_session', methods: ['DELETE'])]
    public function deleteTrainingSession(int $id, ManagerRegistry $doctrine): JsonResponse
    {
        $em = $doctrine->getManager();
        $trainingSession = $em->getRepository(TrainingSession::class)->find($id);

        if (!$trainingSession) {
            return $this->json(['error' => 'Training session n\'est pas trouvé'], 404);
        }

        $em->remove($trainingSession);
        $em->flush();

        return $this->json(['message' => 'Training session supprimé avec success']);
    }


    //**************
    #[Route('/create-medical-cost', name: 'create_medical_cost', methods: ['POST'])]
    public function createMedicalCost(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['joueur_id']) || empty($data['joueur_id'])) {
            return new JsonResponse(['error' => 'L\'id du joueur est requis'], 400);
        }

        if (!isset($data['description']) || empty($data['description'])) {
            return new JsonResponse(['error' => 'La description est requis'], 400);
        }

        $joueur = $entityManager->getRepository(Joueur::class)->find($data['joueur_id']);
        if (!$joueur) {
            return new JsonResponse(['error' => 'Joueur non trouvé'], 404);
        }

        $medicalCost = new MedicalCost();
        $medicalCost->setDescription($data['description']);
        $medicalCost->setCosts($data['costs']);
        $medicalCost->setJoueur($joueur);

        $entityManager->persist($medicalCost);
        $entityManager->flush();

        return new JsonResponse([
            'message' => 'Coût médical créé avec succès',
            'medical_cost' => [
                'id' => $medicalCost->getId(),
                'description' => $medicalCost->getDescription(),
                'costs' => $medicalCost->getCosts(),
                'joueur_id' => $joueur->getId()
            ]
        ], 201);
    }

    // ***** 

    #[Route('/list-medical-costs', name: 'list_medical_costs', methods: ['GET'])]
    public function listMedicalCosts(EntityManagerInterface $entityManager): JsonResponse
    {
        $medicalCosts = $entityManager->getRepository(MedicalCost::class)->findAll();

        $costsArray = [];
        foreach ($medicalCosts as $cost) {
            $joueur = $cost->getJoueur();
            $costsArray[] = [
                'id' => $cost->getId(),
                'description' => $cost->getDescription(),
                'costs' => $cost->getCosts(),
                'joueur' => [
                    'id' => $joueur->getId(),
                    'firstname' => $joueur->getFirstname(),
                    'lastname' => $joueur->getLastname(),
                ]
            ];
        }

        return new JsonResponse($costsArray);
    }

    // *****

    #[Route('/update-medical-cost/{id}', name: 'update_medical_cost', methods: ['PUT'])]
    public function updateMedicalCost(int $id, Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $medicalCost = $entityManager->getRepository(MedicalCost::class)->find($id);

        $joueur = $medicalCost->getJoueur();

        if (!$medicalCost) {
            return new JsonResponse(['error' => 'Coût médical non trouvé'], 404);
        }

        $data = json_decode($request->getContent(), true);

        if (isset($data['description']) && !empty($data['description'])) {
            $medicalCost->setDescription($data['description']);
        }

        if (isset($data['costs']) && !empty($data['costs'])) {
            $medicalCost->setCosts($data['costs']);
        }

        $entityManager->flush();

        return new JsonResponse([
            'message' => 'Coût médical mis à jour avec succès',
            'medical_cost' => [
                'id' => $medicalCost->getId(),
                'description' => $medicalCost->getDescription(),
                'costs' => $medicalCost->getCosts(),
                'joueur' => [
                    'id' => $joueur->getId(),
                    'firstname' => $joueur->getFirstname(),
                    'lastname' => $joueur->getLastname(),
                ]
            ]
        ]);
    }

    // ***********

    #[Route('/delete-medical-cost/{id}', name: 'delete_medical_cost', methods: ['DELETE'])]
    public function deleteMedicalCost(int $id, EntityManagerInterface $entityManager): JsonResponse
    {
        $medicalCost = $entityManager->getRepository(MedicalCost::class)->find($id);

        if (!$medicalCost) {
            return new JsonResponse(['error' => 'Coût médical non trouvé'], 404);
        }

        $entityManager->remove($medicalCost);
        $entityManager->flush();

        return new JsonResponse(['message' => 'Coût médical supprimé avec succès']);
    }

    // *********

    #[Route('/medical-costs/joueur/{joueurId}', name: 'get_medical_costs_by_joueur', methods: ['GET'])]
    public function getMedicalCostsByJoueur(int $joueurId, EntityManagerInterface $entityManager): JsonResponse
    {
        // Récupérer le joueur
        $joueur = $entityManager->getRepository(Joueur::class)->find($joueurId);

        if (!$joueur) {
            return new JsonResponse(['error' => 'Joueur non trouvé'], 404);
        }

        // Récupérer les coûts médicaux associés à ce joueur
        $medicalCosts = $entityManager->getRepository(MedicalCost::class)->findBy(['joueur' => $joueur]);

        // Vérifier si le joueur n'a pas de coûts médicaux
        if (empty($medicalCosts)) {
            return new JsonResponse(['message' => 'Aucun coût médical trouvé pour ce joueur'], 200);
        }

        // Récupérer les coûts médicaux
        $costsArray = [];
        foreach ($medicalCosts as $cost) {
            $costsArray[] = [
                'id' => $cost->getId(),
                'description' => $cost->getDescription(),
                'costs' => $cost->getCosts(),
            ];
        }

        return new JsonResponse($costsArray);
    }
    


}