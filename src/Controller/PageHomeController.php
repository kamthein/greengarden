<?php

namespace App\Controller;

use App\Entity\Defis;
use App\Entity\Flux;
use App\Entity\Friend;
use App\Entity\Post;
use App\Entity\Recolte;
use App\Repository\FluxRepository;
use App\Repository\FriendRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PageHomeController extends AbstractController
{

    #[Route(path: '/', name: 'home')] // Afficher la page d'acceuil
    public function index(ChartBuilderInterface $chartBuilder,PaginatorInterface $paginator, FluxRepository $fluxRepository)
    {
        if ($this->isGranted('ROLE_USER')){
            
            $flux = $this->getFlux_tous($fluxRepository);
            return $this->render('home/index.html.twig', [
                'flux' => $flux,
            ]);

        } else {
            return $this->redirectToRoute('app_login');
        }
    }


    #[Route(path: '/full_photo', name: 'home_photo')] // Afficher page home_photo
    public function home_photo(ChartBuilderInterface $chartBuilder,PaginatorInterface $paginator, FluxRepository $fluxRepository)
    {
        if ($this->isGranted('ROLE_USER')){

            $mosaique = true;
            $flux_photo = array_merge_recursive($this->getFlux_photo_panier($fluxRepository),$this->getFlux_photo_post($fluxRepository));
            return $this->render('home/index.html.twig', [
                'flux' => $flux_photo,
                'mosaique' => $mosaique,
            ]);
        } else {
            return $this->redirectToRoute('app_login');
        }
    }

    #[Route(path: '/full_amis', name: 'home_amis')] // Afficher page index_amis
    public function index_amis(ChartBuilderInterface $chartBuilder,UserInterface $user, PaginatorInterface $paginator, FriendRepository $friendRepository, FluxRepository $fluxRepository)
    {
        if ($this->isGranted('ROLE_USER')){

            $flux_amis = $this->getFlux_amis($user, $friendRepository, $fluxRepository);
            return $this->render('home/index.html.twig', [
                'flux' =>  $flux_amis,
            ]);
        } else {
            return $this->redirectToRoute('app_login');
        }
    }

    #[Route(path: '/oneflux/{id}', name: 'oneflux')] // Afficher un flux seulement
    public function oneflux(ChartBuilderInterface $chartBuilder, int $id, FluxRepository $fluxRepository)
    {
        if ($this->isGranted('ROLE_USER')){
            $flux = $fluxRepository->find($id);
            return $this->render('home/one_flux.html.twig', [
                'flux' =>  $flux,
            ]);
        } else {
            return $this->redirectToRoute('app_login');
        }
    }

    /**
     * @return mixed
     * Permet de récupérer les flux avec des photos
     */
    public function getFlux_photo_panier(FluxRepository $fluxRepository)
    {
        return $fluxRepository->flux_photo_panier();
    }

    /**
     * @return mixed
     * Permet de récupérer les flux avec des photos
     */
    public function getFlux_photo_post(FluxRepository $fluxRepository)
    {
        return $fluxRepository->flux_photo_post();
    }


    /**
     * @return mixed
     * Permet de récupérer tous les flux
     */
    public function getFlux_tous(FluxRepository $fluxRepository)
    {
        return $fluxRepository->flux_tous();
    }

    /**
     * @param $amis
     * @return mixed
     * Permet de récupérer les flux des amis suivis
     */
    public function getFlux_amis($user, FriendRepository $friendRepository, FluxRepository $fluxRepository)
    {
        $amis = $friendRepository->findfriendfollowedBy($user);
        return $fluxRepository->flux_amis($amis);
    }

}