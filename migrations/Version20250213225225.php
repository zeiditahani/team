<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250213225225 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE contrat_photographe_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE contrat_photographe (id INT NOT NULL, photographe_id INT NOT NULL, salaire DOUBLE PRECISION NOT NULL, date_affectation DATE NOT NULL, date_fin_contrat DATE DEFAULT NULL, statut BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_8A02C4AE97DB59CB ON contrat_photographe (photographe_id)');
        $this->addSql('ALTER TABLE contrat_photographe ADD CONSTRAINT FK_8A02C4AE97DB59CB FOREIGN KEY (photographe_id) REFERENCES photographe (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE contrat_photographe_id_seq CASCADE');
        $this->addSql('ALTER TABLE contrat_photographe DROP CONSTRAINT FK_8A02C4AE97DB59CB');
        $this->addSql('DROP TABLE contrat_photographe');
    }
}
