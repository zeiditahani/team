<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250213232354 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE contrat_entraineur_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE contrat_entraineur (id INT NOT NULL, entraineur_id INT NOT NULL, salaire DOUBLE PRECISION NOT NULL, date_affectation DATE NOT NULL, date_fin_contrat DATE DEFAULT NULL, statut BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_80128BECF8478A1 ON contrat_entraineur (entraineur_id)');
        $this->addSql('ALTER TABLE contrat_entraineur ADD CONSTRAINT FK_80128BECF8478A1 FOREIGN KEY (entraineur_id) REFERENCES entraineur (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE entraineur DROP date_affectation');
        $this->addSql('ALTER TABLE entraineur DROP date_fin_contrat');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE contrat_entraineur_id_seq CASCADE');
        $this->addSql('ALTER TABLE contrat_entraineur DROP CONSTRAINT FK_80128BECF8478A1');
        $this->addSql('DROP TABLE contrat_entraineur');
        $this->addSql('ALTER TABLE entraineur ADD date_affectation DATE NOT NULL');
        $this->addSql('ALTER TABLE entraineur ADD date_fin_contrat DATE DEFAULT NULL');
    }
}
