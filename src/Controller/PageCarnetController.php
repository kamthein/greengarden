<?php

namespace App\Controller;

use App\Entity\Achat;
use App\Entity\Flux;
use DateTime;
use App\Repository\FluxRepository;
use App\Repository\AchatRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class PageCarnetController extends AbstractController
{
    /**
     * @Route("/carnet/{date}", name="carnet")
     * Afficher la page Carnet (pour ajouter des élèments à son jardin)
     */
    public function pageCarnet(UserInterface $user, ManagerRegistry $doctrine, DateTime $date, FluxRepository $fluxRepository, AchatRepository $achatRepository): Response
    {
        $entityManager = $doctrine->getManager();
        $this->CompteurCo($user, $entityManager);

        // Liste des flux du jour : panier, recolte, post
        $fluxes_day = $fluxRepository->fluxbyuserToday($user, $date);

        // Liste des achats du jour
        $achats_day = $achatRepository->achatbyuserToday($user, $date);

        $date_jour = $date->format('Y-m-d H:i:s');
        $mois = $date->format('m');
        $annee = $date->format('Y');

        $month = new Calendrier($mois, $annee);
        $date_formatee = $date->format('d')." ".$month->DatetoString();
        $date_mois = $month->DatetoString();
        $num_semaine = $month->GetWeeks();
        $startingday = $month->getStartingDay()->modify('Monday this week');
        $days = $month->days;

        // Liste des flux du mois : post
        $events_note = $fluxRepository->dateFluxNote($user, $startingday, (clone $startingday)->modify('+ 32 days'));
        // Liste des flux du mois : plantation
        $events_plantation = $fluxRepository->dateFluxPlant($user, $startingday, (clone $startingday)->modify('+ 32 days'));
        // Liste des flux du mois : recolte
        $events_recolte = $fluxRepository->dateFluxRecolte($user, $startingday, (clone $startingday)->modify('+ 32 days'));
        // Liste des flux du mois : achat
        $events_achat = $achatRepository->dateAchat($user, $startingday, (clone $startingday)->modify('+ 32 days'));

        $event = array_merge($events_note, $events_plantation, $events_recolte, $events_achat);

        // flux vide
        $vide = $fluxRepository->TrouverVide();

        return $this->render('carnet/index.html.twig', [
            'user' => $user,
            'fluxes_day' =>$fluxes_day,
            'achats_day' => $achats_day,
            'date' => $date_jour,
            'date_calendrier' => $date_formatee,
            'date_calendrier_mois' => $date_mois,
            'date_semaine' => $num_semaine,
            'date_premier_jour' => $startingday,
            'jours' => $days,
            'events' => $event,
            'vides' => $vide,
        ]);
    }

    /**
     * @param UserInterface $user
     * @param ManagerRegistry $doctrine
     * Compte le nombre de connexion à la page carnet
     */
    public function CompteurCo(UserInterface $user, $entityManager): void
    {
        $user->setLastCo(new DateTime('now'));
        $nbco = $user->getNbCo();
        $user->setNbCo($nbco + 1);
        $entityManager->flush();
    }
}
