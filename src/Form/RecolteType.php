<?php

namespace App\Form;

use App\Entity\Methode;
use App\Entity\Recolte;
use App\Repository\ConsommableRepository;
use App\Repository\MethodeRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RecolteType extends AbstractType
{
    private ConsommableRepository $repository;
    private MethodeRepository $repository_methode;

    public function __construct(ConsommableRepository $repository, MethodeRepository $repository_methode)
    {
        $this->repository = $repository;
        $this->repository_methode = $repository_methode;

    }
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('consommable', ConsommableEntityType::class, [
                'label' => 'Espèce',
                'attr' => [
                    'class' => 'select2auto'
                ]
            ])
            ->add('methode', EntityType::class, [
            'class' => Methode::class,
            'choices' => $this->repository_methode->getMethodeListForChoices(),
            'choice_label' => function ($methode) {
                return $methode->getNom();
            },
            'label' => 'Méthode de culture'
            ])
            ->add('quantity', NumberType::class, [
                'label' => 'Qté (kg)',
                'required' => true,
                'attr' => [
                    'placeholder' => 'en kilogrammes'
                ]
            ])
        ->add('submit', SubmitType::class,[
            'label' => 'Enregistrer',
             'attr' => [
                 'class' => 'mdl-button-rond mdl-button--raised mdl-button--bleufonce mdl-button--colored',
             ],
          ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Recolte::class,
        ]);
    }
}
