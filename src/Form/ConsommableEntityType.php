<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\Consommable;
use App\Repository\ConsommableRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use function str_repeat;

final class ConsommableEntityType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'class' => Consommable::class,
            'query_builder' => static fn (ConsommableRepository $repository) => $repository->createConsoListQueryBuilder(),
            'choice_label' => static function (Consommable $conso) {
                if ($conso->getTreeLevel() <= 2) {
                    return $conso->getNom();
                }
                return str_repeat('', $conso->getTreeLevel()) . '' . $conso->getNom(). ' (' . $conso->getParent()->getNom(). ')' ;
            },
            'group_by' => static function (Consommable $choice): string {
                return $choice->getParentAtLevel(1)->getNom();
            },
        ]);
    }

    public function getParent(): string
    {
        return EntityType::class;
    }
}
