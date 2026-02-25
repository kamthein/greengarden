<?php

namespace App\Services;

use App\Entity\User;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;

class UserMailerService
{
    private MailerInterface $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function sendRegistrationConfirmationEmail(User $user): void
    {
        $message = (new TemplatedEmail())
            ->from('noreply@greengarden.fr')
            ->to($user->getEmail())
            ->subject('Votre compte a été crée avec succès')
            ->htmlTemplate('registration/mail_confirmation.html.twig')
            ->context(['nickname' => $user->getNickname()]);

        $this->mailer->send($message);
    }

    public function sendPasswordRecoveryEmail(User $user): void
    {
        $message = (new TemplatedEmail())
            ->from('noreply@greengarden.fr')
            ->to($user->getEmail())
            ->subject('Réinitialisation du mot de passe')
            ->htmlTemplate('security/reset_password_mail.html.twig')
            ->context([
                'nickname' => $user->getNickname(),
               'token' => $user->getResetToken(),
            ]);

        $this->mailer->send($message);
    }
}