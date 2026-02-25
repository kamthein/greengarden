<?php

namespace App\Controller;

use App\Entity\Garden;
use App\Form\GardenType;
use App\Repository\GardenRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class GardenController extends AbstractController
{
    #[Route('/garden/edit', name: 'app_garden_edit')]
    public function edit(UserInterface $user, Request $request, GardenRepository $gardenRepository, ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();

        // Récupère le jardin existant ou en crée un nouveau
        $garden = $gardenRepository->findOneBy(['user' => $user]);

        if (!$garden) {
            $garden = new Garden();
            $garden->setUser($user);
            $entityManager->persist($garden);
        }

        $form = $this->createForm(GardenType::class, $garden);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'Votre jardin a été mis à jour !');
            return $this->redirectToRoute('garden');
        }

        return $this->render('garden/garden_edit.html.twig', [
            'form' => $form->createView(),
            'garden' => $garden,
        ]);
    }
}
