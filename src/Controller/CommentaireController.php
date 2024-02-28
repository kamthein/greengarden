<?php

namespace App\Controller;

use App\Entity\Commentaire;
use App\Entity\Flux;
use App\Form\CommentaireType;
use DateTime;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class CommentaireController extends AbstractController
{
    /**
     * @Route("/commentaire/{flux_id}", name="commentaire")
     */
    public function ajoutCommentaire(Request $request, ManagerRegistry $doctrine, $flux_id)

    {
        $entityManager = $doctrine->getManager();
        $flux = $entityManager
            ->getRepository(Flux::class)
            ->find($flux_id);
        $commentaire = new Commentaire();
        $commentaire->setUser(app.user);
        $commentaire->setDateHeureCreation(new DateTime('now'));
        $commentaire->setFlux($flux);

        $form = $this->createForm(CommentaireType::class, $commentaire)->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($commentaire);
            $entityManager->flush();
            $this->addFlash('success', 'Votre commentaire a été publié !');

            return $this->redirectToRoute('home');
        }

        return $this->render('commentaire/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
