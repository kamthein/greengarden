<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PageShopControllerPhpController extends AbstractController
{
    #[Route('/page/shop/controller/php', name: 'shop')]
    public function index(): Response
    {
        return $this->render('shop/index.html.twig', [
            'controller_name' => 'PageShopControllerPhpController',
        ]);
    }
}
