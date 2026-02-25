<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Achat;
use App\Entity\Flux;
use App\Entity\Friend;
use App\Entity\Panier;
use App\Entity\Plant;
use App\Entity\Post;
use App\Entity\Recolte;
use DateTime;
use App\Repository\FluxRepository;
use App\Repository\PostRepository;
use App\Repository\PanierRepository;
use App\Repository\RecolteRepository;
use App\Repository\PlantRepository;
use App\Repository\AchatRepository;
use App\Repository\FriendRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Routing\Annotation\Route;

class JardinSupp extends AbstractController
{
    #[Route(path: '/suppflux/{id}', name: 'supp_flux')] // Supprimer un flux (et le post ou le panier associé)
    public function suppFlux(int $id, ManagerRegistry $doctrine, FluxRepository $fluxRepository): \Symfony\Component\HttpFoundation\RedirectResponse
    {

        $entityManager = $doctrine->getManager();
        $flux = $fluxRepository->find($id);
        $this->RemoveAndFlush($entityManager, $flux);
        $this->addFlash('success', 'Flux supprimé');

        return $this->redirectToRoute('garden');
    }

    #[Route(path: '/supppost/{id}', name: 'supp_post')] // Supprimer un post
    public function suppPost(int $id, ManagerRegistry $doctrine, PostRepository $postRepository): \Symfony\Component\HttpFoundation\RedirectResponse
    {
        $entityManager = $doctrine->getManager();
        $post = $postRepository->find($id);
        $this->RemoveAndFlush($entityManager, $post);
        $this->addFlash('success', 'Post supprimé');

        return $this->RedirectCarnetDate();
    }

    #[Route(path: '/supppanier/{id}', name: 'supp_panier')] // Supprimer un panier et toutes ses récoltes ou plantations
    public function suppPanier(int $id, ManagerRegistry $doctrine, PanierRepository $panierRepository ): \Symfony\Component\HttpFoundation\RedirectResponse
    {
        $entityManager = $doctrine->getManager();
        $panier =  $panierRepository->find($id);
        $this->RemoveAndFlush($entityManager, $panier);
        $this->addFlash('success', 'Panier supprimé');

        return $this->redirectToRoute('garden');
        
    }

    #[Route(path: '/supprecolte/{id}', name: 'supp_recolte')] // Supprimer une récolte (le panier n'est pas supprimé)
    public function suppRecolte(int $id, ManagerRegistry $doctrine, RecolteRepository $recolteRepository): \Symfony\Component\HttpFoundation\RedirectResponse
    {
        $entityManager = $doctrine->getManager();
        $recolte = $recolteRepository->find($id);
        $this->RemoveAndFlush($entityManager, $recolte);
        $this->addFlash('success', 'récolte supprimée');

        return $this->RedirectCarnetDate();
    }


    #[Route(path: '/suppplant/{id}', name: 'supp_plant')] // Supprimer une plantation (le panier n'est pas supprimé)
    public function suppPlant(int $id, ManagerRegistry $doctrine, PlantRepository $planRepository): \Symfony\Component\HttpFoundation\RedirectResponse
    {

        $entityManager = $doctrine->getManager();
        $plant = $planRepository->find($id);
        $this->RemoveAndFlush($entityManager, $plant);
        $this->addFlash('success', 'Plant supprimé');

        return $this->RedirectCarnetDate();
    }


    #[Route(path: '/supppamn/{id}', name: 'supp_achat')] // Supprimer un achat
    public function suppAchat(int $id, ManagerRegistry $doctrine, AchatRepository $achatRepository): \Symfony\Component\HttpFoundation\RedirectResponse
    {
        $entityManager = $doctrine->getManager();
        $achat = $achatRepository->find($id);
        $this->RemoveAndFlush($entityManager, $achat);
        $this->addFlash('success', 'Achat supprimé');

        return $this->RedirectCarnetDate();
    }


    #[Route(path: '/suppami/{id}', name: 'supp_ami')] // Supprimer des amis
    public function suppAmis(int $id, UserInterface $user, ManagerRegistry $doctrine, FriendRepository $friendRepository): \Symfony\Component\HttpFoundation\RedirectResponse
    {
        $entityManager = $doctrine->getManager();
        $ami = $friendRepository->findOneBy(array('user_followed' => $id, 'user_friend' =>$user ));
        $this->RemoveAndFlush($entityManager, $ami);
        $this->addFlash('success', 'Amis supprimé');

        return $this->redirectToRoute('garden');
    }


    /**
     * @return RedirectResponse
     * Permet de rediriger l'utilisateur vers la page Carnet avec la date du jour
     */
    public function RedirectCarnetDate(): RedirectResponse
    {
        $date = new DateTime('now');
        $date_jour = $date->format('Y-m-d');
        $url = $this->generateUrl('carnet', [
            'date' => $date_jour,
        ]);
        return new RedirectResponse($url);
    }

    /**
     * @param ManagerRegistry $doctrine
     * @param $objet
     */
    public function RemoveAndFlush($entityManager, $objet): void
    {
        if ($objet)  {
            $entityManager->remove($objet);
            $entityManager->flush();
        }
    }

}
