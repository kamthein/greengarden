<?php

namespace App\Form;

use App\Entity\Flux;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FluxAchatType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('achat', AchatType::class, [
                'attr' => [
                    'class' => 'd-none',
                ],
                'label' => 'Description',
            ])
            ->add('submit', SubmitType::class, [
                'label' => "Publier",
                'attr' => [
                    'class' => 'mdl-button-rond mdl-button--raised mdl-button--bleufonce mdl-button--colored',
                ],
            ])
    ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Flux::class,
        ]);
    }
}