<?php

namespace App\EventListener;

use App\Entity\Entraineur;
use Doctrine\ORM\Event\PostLoadEventArgs;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;

#[AsEntityListener(event: 'postLoad', entity: Entraineur::class)]
class EntraineurListener
{
    public function postLoad(Entraineur $entraineur, PostLoadEventArgs $event): void
{
    // Récupérer tous les contrats actifs associés au entraineur
    $contrats = $entraineur->getContrats()->filter(function ($contrat) {
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

        // Si la date de fin de contrat est passée, supprimer l'utilisateur
        if ($dateFinContrat < $now) {
            $entityManager = $event->getObjectManager();
            $contrat->setStatut(false);
            $user = $entraineur->getUser();

            if ($user) {
                $entraineur->setUser(null);  // Dissocier l'utilisateur
                $entityManager->remove($user);
                $entityManager->flush();
            }
        }
    }
}
}
