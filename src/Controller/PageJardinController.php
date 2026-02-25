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
    #[Route(path: '/bou', name: 'garden')]
    public function pageJardin(UserInterface $user, ChartBuilderInterface $chartBuilder, ManagerRegistry $doctrine, Request $request, FluxRepository $fluxRepository, GardenRepository $gardenRepository, PanierRepository $panierRepository, AchatRepository $achatRepository, RecolteRepository $recolteRepository, UserRepository $userRepository, FriendRepository $friendRepository): Response
    {
        $entityManager = $doctrine->getManager();

        // Récupère les années disponibles pour cet utilisateur
        $availableYears = $recolteRepository->findAvailableYears($user);

        // Année sélectionnée : paramètre URL ou année courante ou dernière année avec données
        $currentYear = (int) (new DateTime())->format('Y');
        if (empty($availableYears)) {$availableYears = [$currentYear];}
        $selectedYear = (int) $request->query->get('year', $currentYear);

        // Si l'année demandée n'a pas de données, on prend la plus récente disponible
        if (!empty($availableYears) && !in_array($selectedYear, $availableYears)) {
            $selectedYear = max($availableYears);
        }

        return $this->statAnnee($user, $user, $chartBuilder, $entityManager, $request, $selectedYear, $availableYears, $fluxRepository, $gardenRepository, $panierRepository, $achatRepository, $recolteRepository, $userRepository, $friendRepository);
    }

    #[Route(path: '/bou/{nickname}', name: 'app_show_garden')]
    public function pageJardinUser(UserInterface $user_co, User $user, ChartBuilderInterface $chartBuilder, ManagerRegistry $doctrine, Request $request, FluxRepository $fluxRepository, GardenRepository $gardenRepository, PanierRepository $panierRepository, AchatRepository $achatRepository, RecolteRepository $recolteRepository, UserRepository $userRepository, FriendRepository $friendRepository): Response
    {
        $entityManager = $doctrine->getManager();

        $availableYears = $recolteRepository->findAvailableYears($user);

        $currentYear = (int) (new DateTime())->format('Y');
        if (empty($availableYears)) {$availableYears = [$currentYear];}
        $selectedYear = (int) $request->query->get('year', $currentYear);

        if (!empty($availableYears) && !in_array($selectedYear, $availableYears)) {
            $selectedYear = max($availableYears);
        }

        return $this->statAnnee($user, $user_co, $chartBuilder, $entityManager, $request, $selectedYear, $availableYears, $fluxRepository, $gardenRepository, $panierRepository, $achatRepository, $recolteRepository, $userRepository, $friendRepository);
    }


    public function statAnnee($user, $user_co, $chartBuilder, $entityManager, $request, $year, $availableYears, $fluxRepository, $gardenRepository, $panierRepository, $achatRepository, $recolteRepository, $userRepository, $friendRepository)
    {
        $user_co->setLastCo(new DateTime('now'));
        $nbco = $user->getNbCo();
        $user_co->setNbCo($nbco + 1);
        $entityManager->flush();

        $garden      = $gardenRepository->findOneby(array('user' => $user));
        $calories    = $this->calories($user, $year, $recolteRepository);
        $p_byuser    = $this->p_byuser($user, $year, $userRepository);
        $r_byuser    = $this->r_byuser($user, $year, $userRepository);
        $chart       = $this->graph_recolte($user, $chartBuilder, $year, $recolteRepository);
        $chart_donR  = $this->graph_repartitionRType($user, $chartBuilder, $year, $recolteRepository);
        $amis        = $this->amis($user, $friendRepository);
        $FluxAchats  = $fluxRepository->achatbyuser($user);

        $templateData = [
            'user'           => $user,
            'p_byuser'       => $p_byuser,
            'r_byuser'       => $r_byuser,
            'chart'          => $chart,
            'chart_don_r'    => $chart_donR,
            'amis'           => $amis,
            'calories'       => $calories,
            'garden'         => $garden,
            'fluxAchats'     => $FluxAchats,
            'selected_year'  => $year,           // ← année active
            'available_years' => $availableYears, // ← liste pour le sélecteur
        ];

        if ($request->get('ajax')) {
            return new JsonResponse([
                'content' => $this->renderView('garden/index.html.twig', $templateData)
            ]);
        }

        return $this->render('garden/index.html.twig', $templateData);
    }


    public function calories($user, $year, $recolteRepository)
    {
        return $recolteRepository->Cal_byuser($user, $year);
    }

    public function p_byuser($user, $year, $userRepository)
    {
        return $userRepository->p_byuser($user, $year);
    }

    public function r_byuser($user, $year, $userRepository)
    {
        return $userRepository->r_byuser($user, $year);
    }

    public function amis($user, $friendRepository)
    {
        return $friendRepository->findfriendBy($user);
    }

    public function graph_repartitionRType($user, ChartBuilderInterface $chartBuilder, $year, $recolteRepository)
    {
        $r_byuserbycat = $recolteRepository->QteBymethodbyuser($user, $year);
        $cat = [];
        $cat_val = [];
        foreach ($r_byuserbycat as $p) {
            $cat[]     = [$p["methode"]];
            $cat_val[] = [$p["quantity_tot"]];
        }

        $chart_donR = $chartBuilder->createChart(Chart::TYPE_DOUGHNUT);
        $chart_donR->setData([
            'labels' => $cat,
            'datasets' => [[
                'data' => $cat_val,
                'backgroundColor' => ['#2633B6', '#eb3b75', '#1c9f27', '#740ebc', '#fbe920', '#757777'],
            ]],
        ]);
        $chart_donR->setOptions([
            'responsive' => true,
            'maintainAspectRatio' => true,
            'aspectRatio' => 2,
        ]);

        return $chart_donR;
    }

    public function graph_recolte($user, $chartBuilder, $year, $recolteRepository)
    {
        $stat = $recolteRepository->statbyuser($user, $year);

        $tab_mois = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12];
        $tab_val = [0];
        $max = 1;

        foreach ($tab_mois as $mois) {
            $found = false;
            foreach ($stat as $s) {
                if ((int)$s["mois"] == $mois) {
                    $tab_val[] = [$s["quantity_tot"]];
                    $found = true;
                    if ($s["quantity_tot"] > $max) {
                        $max = $s["quantity_tot"];
                    }
                }
            }
            if (!$found) {
                $tab_val[] = [0];
            }
        }
        $max *= 1.2;

        $tab_mois = ["", "janvier", "février", "mars", "avril", "mai", "juin", "juillet", "Août", "septembre", "octobre", "novembre", "décembre"];
        $chart = $chartBuilder->createChart(Chart::TYPE_LINE);
        $chart->setData([
            'labels' => $tab_mois,
            'datasets' => [[
                'label' => 'Qté récoltée totale, en kg, par mois',
                'borderColor' => '#2633B6',
                'data' => $tab_val,
                'fill' => false,
                'tension' => 0.1,
            ]],
        ]);
        $chart->setOptions([
            'responsive' => true,
            'scales' => [
                'yAxes' => [['ticks' => ['min' => 0, 'max' => $max]]],
            ],
        ]);

        return $chart;
    }
}