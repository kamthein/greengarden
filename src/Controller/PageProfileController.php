<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Form\UserProfileType;
use App\Repository\PanierRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use function assert;

class PageProfileController extends AbstractController
{
    #[Route(path: '/me', name: 'profile')] // Afficher sa propre page de profil
    public function pageProfil(Request $request, UserInterface $user, UserPasswordHasherInterface $encoder,  ManagerRegistry $doctrine, PanierRepository $panierRepository): Response
    {
        $entityManager = $doctrine->getManager();
        assert($user instanceof User);

        
        $form = $this->createForm(UserProfileType::class, $user)->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($plainPassword = $user->getNewPlainPassword()) {
                $user->setPassword($encoder->hashPassword($user, $user->getNewPlainPassword()));
            }
            $entityManager->flush();
            $this->addFlash('success', 'Votre profil a été mis à jour !');

            return $this->redirectToRoute('profile');
        }
        return $this->render('profile/index.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    #[Route(path: '/parametres', name: 'parametres')] // Afficher la page Paramètres
    public function pageParametre(UserInterface $user): Response
    {
        assert($user instanceof User);

        return $this->render('profile/parametres.html.twig', [
            'user' => $user,
        ]);
    }

}
