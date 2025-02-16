<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250212094149 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE joueur DROP salaire');
        $this->addSql('ALTER TABLE joueur DROP date_affectation');
        $this->addSql('ALTER TABLE joueur DROP date_fin_contrat');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE joueur ADD salaire DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE joueur ADD date_affectation DATE NOT NULL');
        $this->addSql('ALTER TABLE joueur ADD date_fin_contrat DATE DEFAULT NULL');
    }
}
