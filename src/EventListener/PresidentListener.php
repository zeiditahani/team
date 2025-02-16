<?php

namespace App\EventListener;

use App\Entity\President;
use Doctrine\ORM\Event\PostLoadEventArgs;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\EntityManagerInterface;

#[AsEntityListener(event: 'postLoad', entity: President::class)]
class PresidentListener
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function postLoad(President $president, PostLoadEventArgs $event): void
    {
        // Récupérer tous les contrats actifs associés au président
        $contrats = $president->getContrats()->filter(function ($contrat) {
            return $contrat->isStatut() === true; // Filtrer pour ne garder que les contrats actifs
        });

        // Vérifier s'il y a des contrats actifs
        if (!$contrats->isEmpty()) {
            // Récupérer le dernier contrat actif (ou un autre critère si nécessaire)
            $contrat = $contrats->last();

            $dateFinContrat = $contrat->getDateFinContrat();

            // Si la date de fin de contrat est null (CDI), ne rien faire
            if ($dateFinContrat === null) {
                return; // CDI, donc on ne fait rien
            }

            $now = new \DateTime();

            // Si la date de fin de contrat est passée, désactiver le contrat et dissocier l'utilisateur
            if ($dateFinContrat < $now) {
                $contrat->setStatut(false); // Désactiver le contrat

                $user = $president->getUser();
                if ($user) {
                    $president->setUser(null); // Dissocier l'utilisateur
                    $this->entityManager->remove($user); // Supprimer l'utilisateur
                    $this->entityManager->persist($president);
                }

                // Mettre à jour la relation inverse dans Equipe
                $equipe = $president->getEquipe();
                if ($equipe) {
                    $equipe->setPresident(null); // Dissocier le président de l'équipe
                    $this->entityManager->persist($equipe);
                }

                $president -> setEquipe(null);
                // Persister les modifications
                $this->entityManager->persist($contrat);
                $this->entityManager->persist($president);
                if ($equipe) {
                    $this->entityManager->persist($equipe);
                }

                $this->entityManager->flush();

                // Ne pas appeler flush() ici pour éviter les problèmes de transaction
            }
        }
    }
}