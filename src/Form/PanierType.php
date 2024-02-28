<?php

namespace App\Form;

use App\Entity\Panier;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PanierType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titre', TextType::class, [
                'required' => false,
                'label' => false,
               'attr' => [
                   'placeholder' => 'Activité du jour'
               ]
            ])
            ->add('shared')
            ->add('photo', CollectionType::class, [
               'entry_type' => PhotoType::class,
               'by_reference' => false,
               'required' => false,
               'entry_options' => [
                   'vich_imagine_pattern' => 'photo_thumbnail',
               ],
               'allow_add' => true,
               'allow_delete' => true,
               'label' => false,
            ])
            ->add('recoltes', CollectionType::class, [
                'entry_type' => RecolteType::class,
                'by_reference' => false,
                'allow_add' => true,
                'allow_delete' => true,
                'prototype' => true,
                'label' => false,
            ])
            ->add('plants', CollectionType::class, [
                'entry_type' => PlantType::class,
                'by_reference' => false,
                'allow_add' => true,
                'allow_delete' => true,
                'prototype' => true,
                'label' => false,
        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Panier::class,
        ]);
    }
}
