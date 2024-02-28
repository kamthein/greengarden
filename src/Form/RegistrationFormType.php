<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;

final class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'E-mail ',
            ])
            ->add('nickname', null, [
                'label' => 'Pseudo ',
            ])
            ->add('agreeTerms', CheckboxType::class, [
               // 'label' => 'J\'accepte les conditions générales ',
               'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'Merci d\'accepter les conditions générales.',
                    ]),
                ],
            ])
            ->add('newPlainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Vos deux mots de passe ne sont pas identiques.',
                'options' => [
                    'attr' => ['class' => 'password-field'],
                ],
                'label' => false,
                'first_options' => ['label' => 'Mot de passe '],
                'second_options' => ['label' => 'Confirmation du mot de passe '],
            ])

            ->add('submit', SubmitType::class, [
                'label' => "S'inscrire",
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'validation_groups' => ['Default', 'Registration'],
        ]);
    }
}
