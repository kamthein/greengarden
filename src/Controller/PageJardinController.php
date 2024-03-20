<?php

namespace App\Controller;

use App\Entity\Achat;
use App\Entity\Defis;
use App\Entity\Flux;
use App\Entity\Friend;
use App\Entity\Methode;
use App\Entity\Panier;
use App\Entity\Recolte;
use App\Entity\User;
use App\Entity\Garden;
use DateTime;

use App\Repository\FluxRepository;
use App\Repository\PanierRepository;
use App\Repository\AchatRepository;
use App\Repository\RecolteRepository;
use App\Repository\UserRepository;
use App\Repository\FriendRepository;
use App\Repository\GardenRepository;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;


class PageJardinController extends AbstractController
{
    #[Route(path: '/bou', name: 'garden')] // Afficher sa propre page Jardin
    public function pageJardin(UserInterface $user, ChartBuilderInterface $chartBuilder,  ManagerRegistry $doctrine, Request $request, FluxRepository $fluxRepository, GardenRepository $gardenRepository, PanierRepository $panierRepository, AchatRepository $achatRepository, RecolteRepository $recolteRepository, UserRepository $userRepository, FriendRepository $friendRepository): Response
    {
        $entityManager = $doctrine->getManager();
        return $this->statAnnee($user, $user, $chartBuilder, $entityManager, $request, 2024, $fluxRepository, $gardenRepository, $panierRepository, $achatRepository, $recolteRepository, $userRepository, $friendRepository );
    }

       #[Route(path: '/bou/{nickname}', name: 'app_show_garden')] // Afficher la page Jardin d'un autre utilisateur 2024
    public function pageJardinUser(UserInterface $user_co, User $user, ChartBuilderInterface $chartBuilder,  ManagerRegistry $doctrine, Request $request, FluxRepository $fluxRepository, GardenRepository $gardenRepository, PanierRepository $panierRepository, AchatRepository $achatRepository, RecolteRepository $recolteRepository, UserRepository $userRepository, FriendRepository $friendRepository): Response
    {
        $entityManager = $doctrine->getManager();
        return $this->statAnnee($user, $user_co, $chartBuilder, $entityManager, $request, 2024, $fluxRepository, $gardenRepository, $panierRepository, $achatRepository, $recolteRepository, $userRepository, $friendRepository);
    }



    /* Fonction pour calculer toutes les données, en fonction de l'utilisateur et de l'année */
    public function statAnnee( $user, $user_show, $chartBuilder, $entityManager, $request, $year, $fluxRepository, $gardenRepository, $panierRepository, $achatRepository, $recolteRepository, $userRepository, $friendRepository){
        $user->setLastCo(new DateTime('now'));
        $nbco = $user->getNbCo();
        $user->setNbCo($nbco + 1);
        $entityManager->flush();

        // Jardin User
        $garden = $gardenRepository->findOneby(array('user' => $user));
        // Calorie User
        $calories = $this->calories($user, $year, $recolteRepository);
        // Liste des espèces plantées en semis / en plant
        $p_byuser = $this->p_byuser($user, $year, $userRepository);
        // Liste des espèces récoltées 
        $r_byuser = $this->r_byuser($user, $year, $userRepository);
        // Graphique ligne récoltes
        $chart = $this->graph_recolte($user, $chartBuilder, $year, $recolteRepository);
        // Graphique donught récoltées par méthode
        $chart_donR = $this->graph_repartitionRType($user, $chartBuilder, $year, $recolteRepository);
        //Liste des amis
        $amis = $this->amis($user, $friendRepository);
        //Liste des achats
        $FluxAchats = $fluxRepository->achatbyuser($user);


        //On vérifie si on a une requête AJAX
        if($request->get('ajax')) {
            return new JsonResponse([
                'content' => $this->renderView('garden/index.html.twig', [
                    'user' => $user,
                    'p_byuser' => $p_byuser,
                    'r_byuser' => $r_byuser,
                    'chart' => $chart,
                    'chart_don_r' => $chart_donR,
                    'amis' => $amis,
                    'calories' => $calories,
                    'garden' => $garden,
                    'fluxAchats' => $FluxAchats
                ])
            ]);
        }

        return $this->render('garden/index.html.twig', [
            'user' => $user,
            'p_byuser' => $p_byuser,
            'r_byuser' => $r_byuser,
            'chart' => $chart,
            'chart_don_r' => $chart_donR,
            'amis' => $amis,
            'calories' => $calories,
            'garden' => $garden,
            'fluxAchats' => $FluxAchats
        ]);
       
    }


    /**
     * Récupère les calories
     */
    public function calories($user, $year, $recolteRepository)
    {
        return $recolteRepository->Cal_byuser($user, $year);
    }

    /**
     * Récupère la liste des espèces plantées
     */
    public function p_byuser($user, $year, $userRepository)
    {
        return $userRepository->p_byuser( $user, $year);
    }

    /**
     * Récupère la liste des espèces récoltées
     */
    public function r_byuser($user, $year, $userRepository)
    {
        return $userRepository->r_byuser( $user, $year);
    }

    /**
     * Récupère la liste des amis
     */
    public function amis($user, $friendRepository)
    {
        return $friendRepository->findfriendBy($user);
    }


    /**
     * Créer les données pour le graphique dans stat pour la page Jardin (Répartition par espèce récoltée)
     */
    public function graph_repartitionRType($user, ChartBuilderInterface $chartBuilder, $year, $recolteRepository)
    {
        // Liste des espèces récoltées par catégorie
        $r_byuserbycat = $recolteRepository->QteBymethodbyuser($user, $year);
        $cat = [];
        $cat_val = [];
        foreach($r_byuserbycat as $p)
        {
            $cat[] = [$p["methode"]];
            $cat_val[] = [$p["quantity_tot"]];
        }

        $chart_donR = $chartBuilder->createChart(Chart::TYPE_DOUGHNUT);
        $chart_donR->setData([
            'labels' => $cat,
            'datasets' => [
                [
                    'data' => $cat_val,
                    'backgroundColor' => [
                        '#2633B6',
                        '#eb3b75',
                        '#1c9f27',
                        '#740ebc',
                        '#fbe920',
                        '#757777',
                    ],
                ],
            ],
        ]);
        $chart_donR->setOptions([
            'responsive' => true,
        ]);

        return $chart_donR;
    }

    // Créer les données pour le graphique dans stat (Qté récoltée)
    public function graph_recolte($user, $chartBuilder, $year, $recolteRepository)
    {
        $stat = $recolteRepository->statbyuser($user, $year);

        $tab_mois = [1,2,3,4,5,6,7,8,9,10,11,12];
        $tab_val = [0];
        $max = 1;
        $test = false;
        foreach($tab_mois as $mois)
        {
            $test = false;
            foreach($stat as $s)
            {
                if ((int) $s["mois"] == $mois)
                {
                    $tab_val[] = [$s["quantity_tot"]];
                    $test = true;
                    if ($s["quantity_tot"] > $max) {
                        $max = $s["quantity_tot"];
                    }
                }
            }
            if ($test == false) {
                $tab_val[] = [0];
            }
        }
        $max *= 1.2;

        $tab_mois = ["","janvier","février","mars","avril", "mai", "juin", "juillet", "Août", "septembre", "octobre", "novembre", "décembre"];
        $chart = $chartBuilder->createChart(Chart::TYPE_LINE);
        $chart->setData([
            'labels' => $tab_mois,
            'datasets' => [
                [
                    'label' => 'Qté récoltée totale, en kg, par mois',
                    'borderColor' => '#2633B6',
                    'data' => $tab_val,
                    'fill' => false,
                    'tension' => 0.1,
                ],
            ],
        ]);


        $chart->setOptions([
            'responsive' => true,
            'scales' => [
                'yAxes' => [
                    ['ticks' => ['min' => 0, 'max' => $max]],
                ],
            ],
        ]);
        return $chart;
    }


}



