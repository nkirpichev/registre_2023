<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230526083621 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE categorie (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(100) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE facture (id INT AUTO_INCREMENT NOT NULL, projet_id INT DEFAULT NULL, date_facture DATE NOT NULL, total INT NOT NULL, UNIQUE INDEX UNIQ_FE866410C18272 (projet_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE personne (id INT AUTO_INCREMENT NOT NULL, entreprise_id INT DEFAULT NULL, nom VARCHAR(50) NOT NULL, prenom VARCHAR(50) DEFAULT NULL, is_prestataire TINYINT(1) NOT NULL, INDEX IDX_FCEC9EFA4AEAFEA (entreprise_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tache (id INT AUTO_INCREMENT NOT NULL, projet_id INT DEFAULT NULL, prestataire_id INT DEFAULT NULL, employe_id INT DEFAULT NULL, categorie_id INT DEFAULT NULL, description VARCHAR(255) NOT NULL, date_debut DATE NOT NULL, date_achevement DATE DEFAULT NULL, nombre_heure INT DEFAULT NULL, commentaire VARCHAR(255) DEFAULT NULL, INDEX IDX_93872075C18272 (projet_id), INDEX IDX_93872075BE3DB2B7 (prestataire_id), INDEX IDX_938720751B65292 (employe_id), INDEX IDX_93872075BCF5E72D (categorie_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE facture ADD CONSTRAINT FK_FE866410C18272 FOREIGN KEY (projet_id) REFERENCES projet (id)');
        $this->addSql('ALTER TABLE personne ADD CONSTRAINT FK_FCEC9EFA4AEAFEA FOREIGN KEY (entreprise_id) REFERENCES entreprise (id)');
        $this->addSql('ALTER TABLE tache ADD CONSTRAINT FK_93872075C18272 FOREIGN KEY (projet_id) REFERENCES projet (id)');
        $this->addSql('ALTER TABLE tache ADD CONSTRAINT FK_93872075BE3DB2B7 FOREIGN KEY (prestataire_id) REFERENCES personne (id)');
        $this->addSql('ALTER TABLE tache ADD CONSTRAINT FK_938720751B65292 FOREIGN KEY (employe_id) REFERENCES personne (id)');
        $this->addSql('ALTER TABLE tache ADD CONSTRAINT FK_93872075BCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE facture DROP FOREIGN KEY FK_FE866410C18272');
        $this->addSql('ALTER TABLE personne DROP FOREIGN KEY FK_FCEC9EFA4AEAFEA');
        $this->addSql('ALTER TABLE tache DROP FOREIGN KEY FK_93872075C18272');
        $this->addSql('ALTER TABLE tache DROP FOREIGN KEY FK_93872075BE3DB2B7');
        $this->addSql('ALTER TABLE tache DROP FOREIGN KEY FK_938720751B65292');
        $this->addSql('ALTER TABLE tache DROP FOREIGN KEY FK_93872075BCF5E72D');
        $this->addSql('DROP TABLE categorie');
        $this->addSql('DROP TABLE facture');
        $this->addSql('DROP TABLE personne');
        $this->addSql('DROP TABLE tache');
    }
}
