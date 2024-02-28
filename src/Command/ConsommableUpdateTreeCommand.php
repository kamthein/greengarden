<?php

declare(strict_types=1);

namespace App\Command;

use App\Entity\Consommable;
use App\Entity\Iconconso;
use App\Repository\ConsommableRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ConsommableUpdateTreeCommand extends Command
{
    protected static $defaultName = 'app:consommable:update-tree';
    protected static $defaultDescription = 'Update the nested tree of consommables';

    private ManagerRegistry $doctrine;
    private ConsommableRepository $repository;

    public function __construct(ManagerRegistry $entityManager, ConsommableRepository $repository)
    {
        parent::__construct();

        $entityManager = $doctrine->getManager();
        $this->manager = $manager;
        $this->repository = $repository;
    }

    protected function configure()
    {
        $this
            ->setDescription(self::$defaultDescription)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        // POur livraison V1.10.0
        $aromate = $this->repository->findOneBy(['nom' => 'Aromates']);
        $conso1 = new Consommable('Laurier', $aromate);
        $this->manager->persist($conso1);

        // POur livraison V1.9.0 le 1er septembre
        /*
        $aromate = $this->repository->findOneBy(['nom' => 'Aromates']);
        $conso1 = new Consommable('Estragon', $aromate);
        $this->manager->persist($conso1);
        */

        // POur livraison V1.9.0 le 1er Août
        /*
        $tomate = $this->repository->findOneBy(['nom' => 'Tomate']);
        $courgette = $this->repository->findOneBy(['nom' => 'Courgette']);
        $conso1 = new Consommable('Courgette ronde', $courgette);
        $conso2 = new Consommable('Tomates Green Zebra', $tomate);
        $this->manager->persist($conso1);
        $this->manager->persist($conso2);

        $legumes = $this->repository->findOneBy(['nom' => 'Fruits']);
        $canneberge = $this->repository->findOneBy(['nom' => 'Canneberge']);
        $this->repository->persistAsLastChildOf($canneberge, $legumes);

        $chou = $this->repository->findOneBy(['nom' => 'Chou']);
        $brocoli = $this->repository->findOneBy(['nom' => 'Brocoli']);
        $this->repository->persistAsLastChildOf($brocoli, $chou);

        $pommeterre = $this->repository->findOneBy(['nom' => 'Pomme de terre']);
        $conso1 = new Consommable('Amandine', $pommeterre);
        $conso2 = new Consommable('Agata', $pommeterre);
        $conso3 = new Consommable('Apollo', $pommeterre);
        $conso4 = new Consommable('Artemis', $pommeterre);
        $conso5 = new Consommable('Bonnotte', $pommeterre);
        $conso6 = new Consommable('BF 15', $pommeterre);
        $conso7 = new Consommable('Lady Christl', $pommeterre);
        $conso8 = new Consommable('Annabelle', $pommeterre);
        $conso9 = new Consommable('Victoria', $pommeterre);
        $this->manager->persist($conso1);
        $this->manager->persist($conso2);
        $this->manager->persist($conso3);
        $this->manager->persist($conso4);
        $this->manager->persist($conso5);
        $this->manager->persist($conso6);
        $this->manager->persist($conso7);
        $this->manager->persist($conso8);
        $this->manager->persist($conso9);

        $radis = $this->repository->findOneBy(['nom' => 'Radis']);
        $conso1 = new Consommable('Radis rose', $radis);
        $conso2 = new Consommable('Radis red meat', $radis);
        $conso3 = new Consommable('Radis noir', $radis);
        $conso4 = new Consommable('Radis violet de Gournay', $radis);
        $conso5 = new Consommable('Radis Hilds blauer', $radis);
        $conso6 = new Consommable('Radis green meat', $radis);
        $this->manager->persist($conso6);
        $this->manager->persist($conso5);
        $this->manager->persist($conso3);
        $this->manager->persist($conso2);
        $this->manager->persist($conso1);
        $this->manager->persist($conso4);

        $aromate = $this->repository->findOneBy(['nom' => 'Aromates']);
        $conso1 = new Consommable('Basilic', $aromate);
        $this->manager->persist($conso1);
        */

        $this->manager->flush();

        $io->success('Done!');

        return Command::SUCCESS;
    }
}
