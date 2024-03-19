<?php

namespace App\Form;

use App\Entity\State;
use App\Entity\Plant;
use App\Repository\ConsommableRepository;
use App\Repository\StateRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PlantType extends AbstractType
{
    private StateRepository $repository_state;

    public function __construct(ConsommableRepository $repository, StateRepository $repository_state)
    {
        $this->repository_state = $repository_state;

    }
    
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('consommable', ConsommableEntityType::class, [
                'label' => 'Espèce',
                'attr' => [
                    'class' => 'select2auto'
                ]
            ])
            ->add('state', EntityType::class, [
                'class' => State::class,
                'choices' => $this->repository_state->getStateListForChoices(),
                'choice_label' => function ($state) {
                    return $state->getNom();
                },
                'label' => 'Etat'
            ])
            ->add('quantite', NumberType::class, [
                'label' => 'Qté'
            ])
            ->add('submit', SubmitType::class,[
                'label' => 'Enregistrer',
                'attr' => [
                    'class' => 'mdl-button-rond mdl-button--raised mdl-button--bleufonce mdl-button--colored',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
            'data_class' => Plant::class,
        ]);
    }
}
