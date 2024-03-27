<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Achat;
use App\Entity\Consommable;
use App\Entity\Flux;
use App\Entity\Friend;
use App\Entity\Panier;
use App\Entity\Photo;
use App\Entity\Plant;
use App\Entity\Post;
use App\Entity\Recolte;
use App\Entity\User;
use App\Form\FluxPanierType;
use App\Form\FluxPostType;
use App\Form\FluxAchatType;
use App\Form\FriendType;
use App\Form\PlantType;
use App\Form\RecolteType;
use DateTime;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use function assert;

class JardinAjouts extends AbstractController
{

    #[Route(path: '/ajoutpost', name: 'ajouter')] // Ajouter un post (et son flux)
    public function ajoutPost(Request $request, UserInterface $user, ManagerRegistry $doctrine, EntityManagerInterface $em): Response
    {
        $entityManager = $doctrine->getManager();
        assert($user instanceof User);

        $daydate =  new DateTime('now');
        $flux = $this->NewFluxDefault($user, $daydate);
        $post = new Post();
        $post->setCreatedat($daydate);
        $post->setShared(true);
        $image = new Photo();
        $post->addPhoto($image);
        $flux->setPost($post);

        $form = $this->createForm(FluxPostType::class, $flux)->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if (!$image->getImageName()){
                $image->setImageName('aujourdhui.png');
                $image->setImageSize(236);
                $enablecrop = false;
            }

            $this->persistAndFlush($entityManager, $flux);
            $this->addFlash('success', 'Votre note a été publiée !');
            // return $this->redirectToRoute('app_crop_image', ['id'=> $image->getId()]);
            return $this->RedirectCarnetDate();
        }
        return $this->render('carnet/ajouter/addNote.html.twig', [
            'form' => $form->createView(),
        ]);


    }

    #[Route(path: '/ajoutrecolte/{id?0}', name: 'ajouter_recolte')] // Ajouter une récolte à un panier existant
    public function ajoutRecolte(Request $request, UserInterface $user, ManagerRegistry $doctrine, $id, EntityManagerInterface $em): Response
    {
        $entityManager = $doctrine->getManager();
        assert($user instanceof User);

        $daydate = new DateTime('now');

        //si panier non précisé, on prend le panier par défaut
        $panier = ($id == 0) ? $em->getRepository(Panier::class)->panierbyuser($user)[0] : $em->getRepository(Panier::class)->find($id);
        $panier->setUpdatedat($daydate);
        $flux = $panier->getflux();
        $flux->setUpdatedat($daydate);
        $recolte = $this->NewRecolteDefault($panier, $user);

        $form = $this->createForm(RecolteType::class, $recolte)->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->persistAndFlush($entityManager, $recolte);
            $this->addFlash('success', 'Votre récolte a été créé !');
            return $this->RedirectCarnetDate();
        }
        return $this->render('carnet/ajouter/addRecolte.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route(path: '/ajoutplant/{id?0}', name: 'ajouter_plant')] // Ajout d'une plantation dans un panier existant
    public function ajoutPlantation(Request $request, UserInterface $user, ManagerRegistry $doctrine, $id, EntityManagerInterface $em): Response
    {
        $entityManager = $doctrine->getManager();
        assert($user instanceof User);

        $daydate = new DateTime('now');

        $panier = ($id == 0) ? $em->getRepository(Panier::class)->panierbyuser($user)[0] : $em->getRepository(Panier::class)->find($id);
        $panier->setUpdatedat($daydate);
        $flux = $panier->getflux();
        $flux->setUpdatedat($daydate);
        $plant = $this->NewPlantDefault($panier, $user);

        $form = $this->createForm(PlantType::class, $plant)->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->persistAndFlush($entityManager, $plant);
            $this->addFlash('success', 'Votre plantation a été créé.');
            return $this->RedirectCarnetDate();
        }
        return $this->render('carnet/ajouter/addPlant.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route(path: '/ajoutachat', name: 'ajouter_achat')] // Ajouter un achat (et son flux)
    public function ajoutAchat(Request $request, UserInterface $user, ManagerRegistry $doctrine, EntityManagerInterface $em): Response
    {
        $entityManager = $doctrine->getManager();
        assert($user instanceof User);

        $daydate =  new DateTime('now');
        $flux = $this->NewFluxDefault($user, $daydate);

        $achat = new Achat();
        $achat->setShared(true);
        $achat->setCreatedat(new DateTime('now'));
        $achat->setDescritpion("");
        $achat->setUser($user);
        $flux->setAchat($achat);

        $form = $this->createForm(FluxAchatType::class, $flux)->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $achat->setCreatedat(new DateTime('now'));
            $this->persistAndFlush($entityManager, $flux);
            $this->addFlash('success', 'Votre achat a été enregistré !');
            // return $this->redirectToRoute('app_crop_image', ['id'=> $image->getId()]);
            return $this->RedirectCarnetDate();
        }
        return $this->render('carnet/ajouter/addAchat.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route(path: '/ajoutami', name: 'ajout_amis')] // AJouter des personnes à suivre
    public function ajoutAmis(Request $request, UserInterface $user, ManagerRegistry $doctrine, EntityManagerInterface $em): Response
    {
        $entityManager = $doctrine->getManager();
        // Liste des utilisateurs
        $users = $em->getRepository(User::class)
            ->findBy(array(), array('nickname' => 'ASC'));
        //Liste des amis
        $amis = $em->getRepository(Friend::class)
            ->findBy(array('user_friend' => $user));
        $friend = new Friend();
        $form = $this->createForm(FriendType::class, $friend);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $inputValue = $request->get("friendzone");
            if ($inputValue)
            {
                foreach ($inputValue as $fri)
                {
                    $followed = $em->getRepository(User::class)
                        ->find($fri);
                    $friend = new Friend();
                    $friend->setUserFriend($user);
                    $friend->setUserFollowed($followed);
                    $entityManager->persist($friend);
                }
                $entityManager->flush();
            }
            return $this->redirectToRoute('garden');
        }
        return $this->render('garden/addAmis.html.twig', [
            'users' => $users,
            'amis' => $amis,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param ManagerRegistry $doctrine
     * @param $objet
     */
    public function persistAndFlush($entityManager, $objet): void
    {

        if ($objet)  {
            $entityManager->persist($objet);
            $entityManager->flush();
        }
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
     * @param $user
     * @return Flux
     * Créer un nouveau flux avec la date du jour et l'utilisateur connecté
     */
    public function NewFluxDefault($user, DateTime $daydate): Flux
    {
        $flux = new Flux();
        $flux->setUser($user);
        $flux->setShared(true);
        $flux->setCreatedat($daydate);
        $flux->setUpdatedat($daydate);
        return $flux;
    }

    /**
     * @return Panier
     * Créer un nouveau panier avec la date du jour
     */
    public function NewPanierDefault(DateTime $daydate): Panier
    {
        $panier = new Panier();
        $panier->setCreatedat($daydate);
        $panier->setShared(true);
        return $panier;
    }

    /**
     * @param $user
     * @return Plant
     * Créer une nouvelle plantation dans un panier
     */
    public function NewPlantDefault(Panier $panier, $user): Plant
    {
        $plant = new Plant();
        $plant->setPanier($panier);
        $plant->setCreatedat(new DateTime('now'));
        $plant->setUser($user);
        $panier->addPlant($plant);
        return $plant;
    }

    /**
     * @param $user
     */
    public function NewRecolteDefault(Panier $panier, $user): Recolte
    {
        $recolte = new Recolte();
        $recolte->setPanier($panier);
        $recolte->setCreatedat(new DateTime('now'));
        $recolte->setUser($user);
        $panier->addRecolte($recolte);
        return $recolte;
    }


}
