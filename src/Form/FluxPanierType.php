<?php

namespace App\Form;

use App\Entity\Flux;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FluxPanierType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('panier', PanierType::class, [
                'attr' => [
                    'class' => 'd-none',
                ],
                'label' => false,
            ])
            ->add('submit', SubmitType::class, [
                'label' => "Enregistrer",
                  'attr' => [
                'class' => 'mdl-button-rond mdl-button--raised mdl-button--bleufonce mdl-button--colored',
                ],
           ])
    ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Flux::class,
        ]);
    }
}
