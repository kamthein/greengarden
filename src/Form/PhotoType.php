<?php

namespace App\Form;

use App\Entity\Photo;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;

class PhotoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('imageFile', VichImageType::class, [
                'required' => true,
                'allow_delete' => false,
                'delete_label' => '',
                'download_label' => '',
                'download_link' => false,
                'imagine_pattern' => $options['vich_imagine_pattern'],
                'asset_helper' => true,
                'label' => false,
                'attr' => [
                    'class' => 'vich-form-image',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Photo::class,
            'vich_imagine_pattern' => 'user_mini_thumbnail',
        ]);
    }
}
