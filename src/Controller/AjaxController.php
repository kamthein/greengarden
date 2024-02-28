<?php

namespace App\Controller;

use App\Entity\Commentaire;
use App\Entity\Flux;
use App\Entity\Friend;
use App\Entity\Like;
use App\Entity\User;
use App\Repository\FriendRepository;
use App\Repository\LikeRepository;
use DateTime;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use function assert;
use function json_decode;

/**
 * @Route("/ajax")
 */
class AjaxController extends AbstractController
{

    /**
     * @Route("/like/{id}", name="like", methods={"POST"})
     * Liker un flux (ou deliker)
     */
    public function like(Flux $flux, LikeRepository $likeRepository,  ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();
        $user = $this->getUser();
        assert($user instanceof User);
        $like = $likeRepository->findOneBy(['flux' => $flux, 'user' => $user]);
        if ($like instanceof Like) {
            $entityManager->remove($like);
        } else {
            $entityManager->persist(new Like($flux, $user));
        }
        $entityManager->flush();
        $nbr_like = $likeRepository->count(['flux' => $flux]);
        return new JsonResponse($nbr_like);
    }


    /**
     * @Route("/ami/{id}", name="ami", methods={"POST"})
     * Ajouter une personne à suivre depuis la page de son profil, ou ne plus suivre
     */
    public function addFriend(User $user, FriendRepository $friendRepository, ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();
        $user_co = $this->getUser();
        assert($user instanceof User);
        $friend = $friendRepository->findOneBy(['user_followed' => $user, 'user_friend' => $user_co]);
        if ($friend instanceof Friend) {
            $entityManager->remove($friend);
            $suiveur = 0;
        } else {
            $entityManager->persist(new Friend($user_co, $user));
            $suiveur = 1;
        }
        $entityManager->flush();
        return new JsonResponse($suiveur);
    }

    /**
     * @Route("/addcomment/{id}", name="addcomment", methods={"POST"})
     */
    public function addcomment(Request $request, Flux $flux, ManagerRegistry $doctrine): Response
    {

        $entityManager = $doctrine->getManager();
        $data = json_decode($request->request->get('contenu'));
        $data = $request->request->get('contenu');
        if (empty($data)) {
            throw new BadRequestHttpException('Commentaire vide!');
        }
        $user = $this->getUser();
        assert($user instanceof User);
        $commentaire = new Commentaire();
        $commentaire->setFlux($flux);
        $commentaire->setUser($user);
        $commentaire->setContenu($data);
        $commentaire->setDateHeureCreation(new DateTime('now'));
        $entityManager->persist($commentaire);
        $entityManager->flush();
        $tab = array (
                "user" => $user->getNickname(),
                "contenu" => $data,
                "date" => $commentaire->getDateHeureCreation()->format("d/m \à H:i"),
        );
        return new JsonResponse($tab);
    }

}
