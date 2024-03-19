<?php

namespace App\Form;

use App\Entity\Taille;
use App\Entity\User;
use App\Entity\ZoneAdministrative;
use App\Repository\TailleRepository;
use App\Repository\ZoneAdministrativeRepository;
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
    private ZoneAdministrativeRepository $repository;
    private TailleRepository $repository_taille;

    public function __construct(ZoneAdministrativeRepository $repository, TailleRepository $repository_taille)
    {
        $this->repository = $repository;
        $this->repository_taille = $repository_taille;
    }

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
            ->add('administrativeArea', EntityType::class, [
                'class' => ZoneAdministrative::class,
                'choices' => $this->repository->getZonesListForChoices(),
                'label' => 'Modifier',
                'required' => false,
            ])
            ->add('surface', EntityType::class, [
                'class' => Taille::class,
                'choices' => $this->repository_taille->getTailleListForChoices(),
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
