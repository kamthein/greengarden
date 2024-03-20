<?php

namespace App\Controller;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PageHomeController extends AbstractController
{

    #[Route(path: '/', name: 'home')] // Afficher la page d'acceuil
    public function index()
    {
        if ($this->isGranted('ROLE_USER')){
            
            return $this->render('home/index.html.twig');

        } else {
            return $this->redirectToRoute('app_login');
        }
    }



}