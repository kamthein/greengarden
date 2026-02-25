<?php

namespace App\Form;

use App\Entity\Garden;
use App\Entity\Taille;
use App\Entity\ZoneAdministrative;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GardenType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('region', EntityType::class, [
                'class' => ZoneAdministrative::class,
                'choice_label' => function (ZoneAdministrative $z) {
                    return $z->getCountryCode() . ' — ' . $z->getName();
                },
                'placeholder' => 'Choisissez votre région',
                'required' => false,
                'label' => 'Région',
            ])
            ->add('surface', EntityType::class, [
                'class' => Taille::class,
                'choice_label' => function (Taille $t) {
                    return $t->getNom() . ' (' . $t->getMetre() . ')';
                },
                'placeholder' => 'Choisissez votre surface',
                'required' => false,
                'label' => 'Surface',
            ])
            ->add('sable', IntegerType::class, [
                'required' => false,
                'label' => 'Sable (%)',
                'attr' => ['min' => 0, 'max' => 100, 'placeholder' => 'ex: 30'],
            ])
            ->add('argile', IntegerType::class, [
                'required' => false,
                'label' => 'Argile (%)',
                'attr' => ['min' => 0, 'max' => 100, 'placeholder' => 'ex: 20'],
            ])
            ->add('calcaire', IntegerType::class, [
                'required' => false,
                'label' => 'Calcaire (%)',
                'attr' => ['min' => 0, 'max' => 100, 'placeholder' => 'ex: 10'],
            ])
            ->add('limon', IntegerType::class, [
                'required' => false,
                'label' => 'Limon (%)',
                'attr' => ['min' => 0, 'max' => 100, 'placeholder' => 'ex: 40'],
            ])
            ->add('serre', IntegerType::class, [
                'required' => false,
                'label' => 'Serre (m²)',
                'attr' => ['placeholder' => 'ex: 12'],
            ])
            ->add('cuve', IntegerType::class, [
                'required' => false,
                'label' => 'Cuves (L)',
                'attr' => ['placeholder' => 'ex: 500'],
            ])
            ->add('minpluvio', IntegerType::class, [
                'required' => false,
                'label' => 'Pluviométrie min (mm)',
                'attr' => ['placeholder' => 'ex: 400'],
            ])
            ->add('maxpluvio', IntegerType::class, [
                'required' => false,
                'label' => 'Pluviométrie max (mm)',
                'attr' => ['placeholder' => 'ex: 800'],
            ])
            ->add('moyenneprod', IntegerType::class, [
                'required' => false,
                'label' => 'Production moyenne (kg/an)',
                'attr' => ['placeholder' => 'ex: 50'],
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Enregistrer',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Garden::class,
        ]);
    }
}
