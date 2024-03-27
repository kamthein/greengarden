<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Services\UserMailerService;
use Doctrine\Persistence\ManagerRegistry;
use LogicException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Uid\Uuid;


final class SecurityController extends AbstractController
{
    public $translator;
    #[Route(path: '/login', name: 'app_login', methods: ['GET', 'POST'])]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        return $this->render('security/login.html.twig', [
            'last_username' => $authenticationUtils->getLastUsername(),
            'error' => $authenticationUtils->getLastAuthenticationError(),
        ]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    #[Route(path: '/resetting', name: 'forgotten_password')]
    public function passwordReset(): \Symfony\Component\HttpFoundation\Response
    {
        return $this->render('security/reset.html.twig');
    }

    #[Route(path: '/forget-password', name: 'reset_password')]
    public function forgottenPassword(Request $request,  ManagerRegistry $doctrine, UserRepository $userRepository, UserMailerService $mailer): Response
    {
        $entityManager = $doctrine->getManager();
        if ($request->isMethod('POST')) {
            $email = $request->request->get('email');

            if (! $user = $userRepository->findOneByEmail($email)) {
                $this->addFlash('danger', 'Désolé, cette adresse mail n\'est pas connue !');

                return $this->redirectToRoute('reset_password');
            }

            \assert($user instanceof User);

            try {
                $user->setResetToken(Uuid::v4()->toRfc4122());
                $user->setFlagResetToken(1);
                $entityManager->flush();
            } catch (\Exception $e) {
                $this->addFlash('warning', $e->getMessage());

                return $this->redirectToRoute('reset_password');
            }

            $mailer->sendPasswordRecoveryEmail($user);
            $this->addFlash('success', 'Vous avez reçu un mail pout réinitialiser votre mot de passe');

            return $this->redirectToRoute('reset_password');
        }

        return $this->render('security/reset.html.twig');
    }

    #[Route(path: '/reset_password/{token}', name: 'app_reset_password')]
    public function resetPassword(Request $request, string $token, ManagerRegistry $doctrine,UserPasswordHasherInterface $passwordEncoder)
    {
        $entityManager = $doctrine->getManager();
        $user = $entityManager->getRepository(User::class)->findOneByResetToken($token);
        if (0 == $user->getFlagResetToken()) {
            $this->addFlash('danger', 'Le lien de réinitialisation a été déjà utilisé');

            return $this->redirectToRoute('app_login');
        }

        if ($request->isMethod('POST')) {
            /* @var $user User */
            if (null === $user) {
                $this->addFlash('danger', 'Token Inconnu');

                return $this->redirectToRoute('app_login');
            }
            $PasswordCheck = preg_match('#[A-Z]#', $request->request->get('newPassword')) + preg_match('#[a-z]#', $request->request->get('newPassword')) + preg_match('#\d#', $request->request->get('newPassword')) + preg_match('#[^a-zA-Z0-9]#', $request->request->get('newPassword'));
            if ($request->request->get('newPassword') == "") {
                $this->addFlash('danger', 'Veuillez remplir les champs !');
                return $this->redirectToRoute('app_reset_password', ['token' => $token]);
            } elseif (true == strpos($request->request->get('newPassword'), $user->getNickname()) || $request->request->get('newPassword') == $user->getNickname()) {
                $this->addFlash('danger', 'Le mot de passe ne doit pas contenir votre pseudo');
                return $this->redirectToRoute('app_reset_password', ['token' => $token]);
            } elseif (strlen($request->request->get('newPassword')) < 8) {
                $this->addFlash('danger', 'Votre mot de passe doit comporter au moins 8 caractères');

                return $this->redirectToRoute('app_reset_password', ['token' => $token]);
            } elseif (4 != $PasswordCheck) {
                $this->addFlash('danger', 'Il faut au moins un chiffre, une lettre majuscule et un symbole non alphanumérique');

                return $this->redirectToRoute('app_reset_password', ['token' => $token]);
            } elseif ($request->request->get('newPassword') != $request->request->get('confirmationPassword')) {
                $this->addFlash('danger', 'Les mots de passe saisis ne sont pas identiques !');

                return $this->redirectToRoute('app_reset_password', ['token' => $token]);
            } else {
                $user->setFlagResetToken(0);
                $user->setPassword($passwordEncoder->hashPassword($user, $request->request->get('newPassword')));
                $entityManager->flush();
                $this->addFlash('success', 'Votre mot de passe a été réinitialisé avec succès!');

                return $this->redirectToRoute('app_login');
            }
        } else {
            return $this->render('security/reset_password.html.twig', ['token' => $token]);
        }
    }


    #[Route(path: '/change_password/{token}', name: 'app_change_password')]
    public function ChangePassword(Request $request, string $token, ManagerRegistry $doctrine, UserPasswordHasherInterface $passwordEncoder)
    {
        
        $entityManager = $doctrine->getManager();
        if ($request->isMethod('POST')) {
            // checking on new password using regex
            $PasswordCheck = preg_match('#[A-Z]#', $request->request->get('newPassword')) + preg_match('#[a-z]#', $request->request->get('newPassword')) + preg_match('#\d#', $request->request->get('newPassword')) + preg_match('#[^a-zA-Z0-9]#', $request->request->get('newPassword'));
            // check if old password is valid
            if (false == $passwordEncoder->isPasswordValid($this->getUser(), $request->request->get('oldPassword'))) {
                $this->addFlash('danger', $this->translator->trans('incorrect_old_password', [], 'user'));
                //dd('test');
                return $this->redirectToRoute('app_change_password', ['token' => $token]);
            }
            // check if the new password contain first name and last Name
            elseif (true == strpos($request->request->get('newPassword'), $this->getUser()->getFirstName()) || true == strpos($request->request->get('newPassword'), $this->getUser()->getLastName())) {
                $this->addFlash('danger', $this->translator->trans('password_contain_name', [], 'user'));

                return $this->redirectToRoute('app_change_password', ['token' => $token]);
                //Check lenght newPassword
            } elseif (strlen($request->request->get('newPassword')) < 8) {
                $this->addFlash('danger', $this->translator->trans('password_length_issue', [], 'user'));

                return $this->redirectToRoute('app_change_password', ['token' => $token]);
            } elseif (4 != $PasswordCheck) {
                $this->addFlash('danger', $this->translator->trans('password_caracter_issue', [], 'user'));

                return $this->redirectToRoute('app_change_password', ['token' => $token]);
                // check if confirmationPassword is equals to newPassword
            } elseif ($request->request->get('newPassword') != $request->request->get('confirmationPassword')) {
                $this->addFlash('danger', $this->translator->trans('passwords_are_different', [], 'user'));

                return $this->redirectToRoute('app_change_password', ['token' => $token]);
            } else {
                // Set the new password
                $this->getUser()->setPassword($passwordEncoder->encodePassword($this->getUser(), $request->request->get('newPassword')));
                $entityManager->flush();
                $this->addFlash('success', $this->translator->trans('password_changed_successfuly', [], 'user'));

                return $this->redirectToRoute('app_login');
            }
        } else {
            return $this->render('security/change_password.html.twig', ['token' => $token]);
        }
    }
}
