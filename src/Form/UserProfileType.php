<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserProfileType extends AbstractType
{



    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('avatar', PhotoType::class, [
                'label' => false,
                'required' => false,
            ])

            ->add('nickname', null, [
                'attr' => [
                    'class' => 'd-none',
                ],
                'label' => 'Modifier',
            ])
            ->add('age', IntegerType::class, [
                'label' => 'Modifier',
                'required' => false,
            ])
            ->add('newPlainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'required' => false,
                'first_options' => [
                    'label' => 'Nouveau mot de passe',
                ],
                'second_options' => [
                    'label' => 'Confirmation',
                ],
                'invalid_message' => 'Les deux mots de passe ne correspondent pas !',
            ])

            ->add('telephone', TextType::class, [
                'label' => 'Modifier',
                'required' => false,
            ])
            ->add('actualPlainPassword', PasswordType::class, [
              'required' => false,
              'label' => 'Taper le mot de passe actuel pour sauvegarder les modifications',
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Enregistrer les modifications',
                'attr' => [
                    'class' => 'mdl-button-rond mdl-button--raised mdl-button--bleufonce mdl-button--colored',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'validation_groups' => ['Default', 'Profile'],
        ]);
    }
}
