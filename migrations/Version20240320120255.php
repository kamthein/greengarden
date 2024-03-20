<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240320120255 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE achat (id INT AUTO_INCREMENT NOT NULL, type_id INT NOT NULL, flux_id INT DEFAULT NULL, descritpion VARCHAR(255) DEFAULT NULL, createdat DATETIME NOT NULL, shared TINYINT(1) NOT NULL, quantite INT NOT NULL, prix DOUBLE PRECISION NOT NULL, INDEX IDX_26A98456C54C8C93 (type_id), UNIQUE INDEX UNIQ_26A98456C85926E (flux_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE commentaire (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, commentaire_id INT DEFAULT NULL, flux_id INT DEFAULT NULL, contenu LONGTEXT NOT NULL, date_heure_creation DATETIME NOT NULL, INDEX IDX_67F068BCA76ED395 (user_id), INDEX IDX_67F068BCBA9CD190 (commentaire_id), INDEX IDX_67F068BCC85926E (flux_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE config (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, version DOUBLE PRECISION NOT NULL, default_user VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, logo VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE consommable (id INT AUTO_INCREMENT NOT NULL, parent_id INT DEFAULT NULL, tree_left INT DEFAULT 0 NOT NULL, tree_level INT DEFAULT 0 NOT NULL, tree_right INT DEFAULT 0 NOT NULL, nom VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, icon_lien VARCHAR(255) DEFAULT NULL, badge1 VARCHAR(255) DEFAULT NULL, badge2 VARCHAR(255) DEFAULT NULL, badge3 VARCHAR(255) DEFAULT NULL, prix DOUBLE PRECISION DEFAULT NULL, calorie DOUBLE PRECISION DEFAULT NULL, INDEX IDX_A04C7F4D727ACA70 (parent_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE flux (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, createdat DATETIME NOT NULL, updatedat DATETIME NOT NULL, shared TINYINT(1) NOT NULL, INDEX IDX_7252313AA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE friend (id INT AUTO_INCREMENT NOT NULL, user_friend_id INT NOT NULL, user_followed_id INT NOT NULL, INDEX IDX_55EEAC616AB4D50C (user_friend_id), INDEX IDX_55EEAC61704D3985 (user_followed_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE garden (id INT AUTO_INCREMENT NOT NULL, surface_id INT DEFAULT NULL, region_id INT DEFAULT NULL, user_id INT DEFAULT NULL, sable INT DEFAULT NULL, argile INT DEFAULT NULL, calcaire INT DEFAULT NULL, limon INT DEFAULT NULL, serre INT DEFAULT NULL, cuve INT DEFAULT NULL, minpluvio INT DEFAULT NULL, maxpluvio INT DEFAULT NULL, moyenneprod INT DEFAULT NULL, INDEX IDX_3C0918EACA11F534 (surface_id), INDEX IDX_3C0918EA98260155 (region_id), INDEX IDX_3C0918EAA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `like` (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, flux_id INT NOT NULL, INDEX IDX_AC6340B3A76ED395 (user_id), INDEX IDX_AC6340B3C85926E (flux_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE methode (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, image_lien VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE panier (id INT AUTO_INCREMENT NOT NULL, flux_id INT DEFAULT NULL, description VARCHAR(255) DEFAULT NULL, createdat DATETIME NOT NULL, updatedat DATETIME NOT NULL, titre VARCHAR(255) DEFAULT NULL, shared TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_24CC0DF2C85926E (flux_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE photo (id INT AUTO_INCREMENT NOT NULL, panier_id INT DEFAULT NULL, post_id INT DEFAULT NULL, user_id INT DEFAULT NULL, image_name VARCHAR(255) NOT NULL, image_size INT NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_14B78418F77D927C (panier_id), INDEX IDX_14B784184B89032C (post_id), UNIQUE INDEX UNIQ_14B78418A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE plant (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, consommable_id INT NOT NULL, state_id INT NOT NULL, panier_id INT DEFAULT NULL, createdat DATETIME NOT NULL, description VARCHAR(255) DEFAULT NULL, quantite DOUBLE PRECISION NOT NULL, INDEX IDX_AB030D72A76ED395 (user_id), INDEX IDX_AB030D72C9CEB381 (consommable_id), INDEX IDX_AB030D725D83CC1 (state_id), INDEX IDX_AB030D72F77D927C (panier_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE post (id INT AUTO_INCREMENT NOT NULL, flux_id INT DEFAULT NULL, description VARCHAR(1000) DEFAULT NULL, createdat DATETIME NOT NULL, titre VARCHAR(255) DEFAULT NULL, shared TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_5A8A6C8DC85926E (flux_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE recolte (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, consommable_id INT NOT NULL, methode_id INT DEFAULT NULL, panier_id INT DEFAULT NULL, createdat DATETIME NOT NULL, quantity DOUBLE PRECISION DEFAULT NULL, INDEX IDX_3433713CA76ED395 (user_id), INDEX IDX_3433713CC9CEB381 (consommable_id), INDEX IDX_3433713C41CCC9A4 (methode_id), INDEX IDX_3433713CF77D927C (panier_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE state (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE taille (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, metre VARCHAR(255) DEFAULT NULL, objectif DOUBLE PRECISION DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE type (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, nickname VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', age INT DEFAULT NULL, telephone VARCHAR(255) DEFAULT NULL, is_verified TINYINT(1) NOT NULL, last_co DATETIME DEFAULT NULL, nb_co DOUBLE PRECISION DEFAULT NULL, reset_token VARCHAR(255) DEFAULT NULL, flag_reset_token INT DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE zone_administrative (id INT AUTO_INCREMENT NOT NULL, country_code VARCHAR(255) DEFAULT NULL, name VARCHAR(255) DEFAULT NULL, meteolink VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE achat ADD CONSTRAINT FK_26A98456C54C8C93 FOREIGN KEY (type_id) REFERENCES type (id)');
        $this->addSql('ALTER TABLE achat ADD CONSTRAINT FK_26A98456C85926E FOREIGN KEY (flux_id) REFERENCES flux (id)');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT FK_67F068BCA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT FK_67F068BCBA9CD190 FOREIGN KEY (commentaire_id) REFERENCES commentaire (id)');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT FK_67F068BCC85926E FOREIGN KEY (flux_id) REFERENCES flux (id)');
        $this->addSql('ALTER TABLE consommable ADD CONSTRAINT FK_A04C7F4D727ACA70 FOREIGN KEY (parent_id) REFERENCES consommable (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE flux ADD CONSTRAINT FK_7252313AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE friend ADD CONSTRAINT FK_55EEAC616AB4D50C FOREIGN KEY (user_friend_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE friend ADD CONSTRAINT FK_55EEAC61704D3985 FOREIGN KEY (user_followed_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE garden ADD CONSTRAINT FK_3C0918EACA11F534 FOREIGN KEY (surface_id) REFERENCES taille (id)');
        $this->addSql('ALTER TABLE garden ADD CONSTRAINT FK_3C0918EA98260155 FOREIGN KEY (region_id) REFERENCES zone_administrative (id)');
        $this->addSql('ALTER TABLE garden ADD CONSTRAINT FK_3C0918EAA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE `like` ADD CONSTRAINT FK_AC6340B3A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE `like` ADD CONSTRAINT FK_AC6340B3C85926E FOREIGN KEY (flux_id) REFERENCES flux (id)');
        $this->addSql('ALTER TABLE panier ADD CONSTRAINT FK_24CC0DF2C85926E FOREIGN KEY (flux_id) REFERENCES flux (id)');
        $this->addSql('ALTER TABLE photo ADD CONSTRAINT FK_14B78418F77D927C FOREIGN KEY (panier_id) REFERENCES panier (id)');
        $this->addSql('ALTER TABLE photo ADD CONSTRAINT FK_14B784184B89032C FOREIGN KEY (post_id) REFERENCES post (id)');
        $this->addSql('ALTER TABLE photo ADD CONSTRAINT FK_14B78418A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE plant ADD CONSTRAINT FK_AB030D72A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE plant ADD CONSTRAINT FK_AB030D72C9CEB381 FOREIGN KEY (consommable_id) REFERENCES consommable (id)');
        $this->addSql('ALTER TABLE plant ADD CONSTRAINT FK_AB030D725D83CC1 FOREIGN KEY (state_id) REFERENCES state (id)');
        $this->addSql('ALTER TABLE plant ADD CONSTRAINT FK_AB030D72F77D927C FOREIGN KEY (panier_id) REFERENCES panier (id)');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8DC85926E FOREIGN KEY (flux_id) REFERENCES flux (id)');
        $this->addSql('ALTER TABLE recolte ADD CONSTRAINT FK_3433713CA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE recolte ADD CONSTRAINT FK_3433713CC9CEB381 FOREIGN KEY (consommable_id) REFERENCES consommable (id)');
        $this->addSql('ALTER TABLE recolte ADD CONSTRAINT FK_3433713C41CCC9A4 FOREIGN KEY (methode_id) REFERENCES methode (id)');
        $this->addSql('ALTER TABLE recolte ADD CONSTRAINT FK_3433713CF77D927C FOREIGN KEY (panier_id) REFERENCES panier (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE achat DROP FOREIGN KEY FK_26A98456C54C8C93');
        $this->addSql('ALTER TABLE achat DROP FOREIGN KEY FK_26A98456C85926E');
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY FK_67F068BCA76ED395');
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY FK_67F068BCBA9CD190');
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY FK_67F068BCC85926E');
        $this->addSql('ALTER TABLE consommable DROP FOREIGN KEY FK_A04C7F4D727ACA70');
        $this->addSql('ALTER TABLE flux DROP FOREIGN KEY FK_7252313AA76ED395');
        $this->addSql('ALTER TABLE friend DROP FOREIGN KEY FK_55EEAC616AB4D50C');
        $this->addSql('ALTER TABLE friend DROP FOREIGN KEY FK_55EEAC61704D3985');
        $this->addSql('ALTER TABLE garden DROP FOREIGN KEY FK_3C0918EACA11F534');
        $this->addSql('ALTER TABLE garden DROP FOREIGN KEY FK_3C0918EA98260155');
        $this->addSql('ALTER TABLE garden DROP FOREIGN KEY FK_3C0918EAA76ED395');
        $this->addSql('ALTER TABLE `like` DROP FOREIGN KEY FK_AC6340B3A76ED395');
        $this->addSql('ALTER TABLE `like` DROP FOREIGN KEY FK_AC6340B3C85926E');
        $this->addSql('ALTER TABLE panier DROP FOREIGN KEY FK_24CC0DF2C85926E');
        $this->addSql('ALTER TABLE photo DROP FOREIGN KEY FK_14B78418F77D927C');
        $this->addSql('ALTER TABLE photo DROP FOREIGN KEY FK_14B784184B89032C');
        $this->addSql('ALTER TABLE photo DROP FOREIGN KEY FK_14B78418A76ED395');
        $this->addSql('ALTER TABLE plant DROP FOREIGN KEY FK_AB030D72A76ED395');
        $this->addSql('ALTER TABLE plant DROP FOREIGN KEY FK_AB030D72C9CEB381');
        $this->addSql('ALTER TABLE plant DROP FOREIGN KEY FK_AB030D725D83CC1');
        $this->addSql('ALTER TABLE plant DROP FOREIGN KEY FK_AB030D72F77D927C');
        $this->addSql('ALTER TABLE post DROP FOREIGN KEY FK_5A8A6C8DC85926E');
        $this->addSql('ALTER TABLE recolte DROP FOREIGN KEY FK_3433713CA76ED395');
        $this->addSql('ALTER TABLE recolte DROP FOREIGN KEY FK_3433713CC9CEB381');
        $this->addSql('ALTER TABLE recolte DROP FOREIGN KEY FK_3433713C41CCC9A4');
        $this->addSql('ALTER TABLE recolte DROP FOREIGN KEY FK_3433713CF77D927C');
        $this->addSql('DROP TABLE achat');
        $this->addSql('DROP TABLE commentaire');
        $this->addSql('DROP TABLE config');
        $this->addSql('DROP TABLE consommable');
        $this->addSql('DROP TABLE flux');
        $this->addSql('DROP TABLE friend');
        $this->addSql('DROP TABLE garden');
        $this->addSql('DROP TABLE `like`');
        $this->addSql('DROP TABLE methode');
        $this->addSql('DROP TABLE panier');
        $this->addSql('DROP TABLE photo');
        $this->addSql('DROP TABLE plant');
        $this->addSql('DROP TABLE post');
        $this->addSql('DROP TABLE recolte');
        $this->addSql('DROP TABLE state');
        $this->addSql('DROP TABLE taille');
        $this->addSql('DROP TABLE type');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE zone_administrative');
    }
}
