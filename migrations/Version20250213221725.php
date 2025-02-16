<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250213221725 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE contrat_medecin_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE contrat_medecin (id INT NOT NULL, medecin_id INT NOT NULL, salaire DOUBLE PRECISION NOT NULL, date_affectation DATE NOT NULL, date_fin_contrat DATE DEFAULT NULL, statut BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_B21400B04F31A84 ON contrat_medecin (medecin_id)');
        $this->addSql('ALTER TABLE contrat_medecin ADD CONSTRAINT FK_B21400B04F31A84 FOREIGN KEY (medecin_id) REFERENCES medecin (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE medecin DROP date_affectation');
        $this->addSql('ALTER TABLE medecin DROP date_fin_contrat');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE contrat_medecin_id_seq CASCADE');
        $this->addSql('ALTER TABLE contrat_medecin DROP CONSTRAINT FK_B21400B04F31A84');
        $this->addSql('DROP TABLE contrat_medecin');
        $this->addSql('ALTER TABLE medecin ADD date_affectation DATE NOT NULL');
        $this->addSql('ALTER TABLE medecin ADD date_fin_contrat DATE DEFAULT NULL');
    }
}
