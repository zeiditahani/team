<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250212095227 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE contrat_joueur_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE contrat_joueur (id INT NOT NULL, joueur_id INT NOT NULL, salaire DOUBLE PRECISION NOT NULL, date_affectation DATE DEFAULT NULL, date_fin_contrat DATE DEFAULT NULL, statut BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_8F8C8C29A9E2D76C ON contrat_joueur (joueur_id)');
        $this->addSql('ALTER TABLE contrat_joueur ADD CONSTRAINT FK_8F8C8C29A9E2D76C FOREIGN KEY (joueur_id) REFERENCES joueur (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE contrat_joueur_id_seq CASCADE');
        $this->addSql('ALTER TABLE contrat_joueur DROP CONSTRAINT FK_8F8C8C29A9E2D76C');
        $this->addSql('DROP TABLE contrat_joueur');
    }
}
