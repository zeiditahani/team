<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250212151822 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE medical_cost_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE medical_cost (id INT NOT NULL, joueur_id INT NOT NULL, description TEXT NOT NULL, costs DOUBLE PRECISION DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_4BD53ED4A9E2D76C ON medical_cost (joueur_id)');
        $this->addSql('ALTER TABLE medical_cost ADD CONSTRAINT FK_4BD53ED4A9E2D76C FOREIGN KEY (joueur_id) REFERENCES joueur (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE medical_cost_id_seq CASCADE');
        $this->addSql('ALTER TABLE medical_cost DROP CONSTRAINT FK_4BD53ED4A9E2D76C');
        $this->addSql('DROP TABLE medical_cost');
    }
}
