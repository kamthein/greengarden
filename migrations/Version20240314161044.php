<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240314161044 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE garden (id INT AUTO_INCREMENT NOT NULL, surface_id INT DEFAULT NULL, region_id INT DEFAULT NULL, sable INT DEFAULT NULL, argile INT DEFAULT NULL, calcaire INT DEFAULT NULL, limon INT DEFAULT NULL, serre INT DEFAULT NULL, cuve INT DEFAULT NULL, minpluvio INT DEFAULT NULL, maxpluvio INT DEFAULT NULL, moyenneprod INT DEFAULT NULL, INDEX IDX_3C0918EACA11F534 (surface_id), INDEX IDX_3C0918EA98260155 (region_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE taille (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, metre VARCHAR(255) DEFAULT NULL, objectif DOUBLE PRECISION DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE zone_administrative (id INT AUTO_INCREMENT NOT NULL, country_code VARCHAR(255) DEFAULT NULL, name VARCHAR(255) DEFAULT NULL, meteolink VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE garden ADD CONSTRAINT FK_3C0918EACA11F534 FOREIGN KEY (surface_id) REFERENCES taille (id)');
        $this->addSql('ALTER TABLE garden ADD CONSTRAINT FK_3C0918EA98260155 FOREIGN KEY (region_id) REFERENCES zone_administrative (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE garden DROP FOREIGN KEY FK_3C0918EACA11F534');
        $this->addSql('ALTER TABLE garden DROP FOREIGN KEY FK_3C0918EA98260155');
        $this->addSql('DROP TABLE garden');
        $this->addSql('DROP TABLE taille');
        $this->addSql('DROP TABLE zone_administrative');
    }
}
