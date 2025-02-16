<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250214222226 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE contrat_president_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE contrat_president (id INT NOT NULL, president_id INT NOT NULL, salaire DOUBLE PRECISION NOT NULL, date_affectation DATE NOT NULL, date_fin_contrat DATE DEFAULT NULL, statut BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_E58CA5E4B40A33C7 ON contrat_president (president_id)');
        $this->addSql('ALTER TABLE contrat_president ADD CONSTRAINT FK_E58CA5E4B40A33C7 FOREIGN KEY (president_id) REFERENCES president (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE president DROP date_affectation');
        $this->addSql('ALTER TABLE president DROP date_fin_contrat');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE contrat_president_id_seq CASCADE');
        $this->addSql('ALTER TABLE contrat_president DROP CONSTRAINT FK_E58CA5E4B40A33C7');
        $this->addSql('DROP TABLE contrat_president');
        $this->addSql('ALTER TABLE president ADD date_affectation DATE NOT NULL');
        $this->addSql('ALTER TABLE president ADD date_fin_contrat DATE DEFAULT NULL');
    }
}
