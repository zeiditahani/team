<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250213210115 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE contrat_kine_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE kine_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE contrat_kine (id INT NOT NULL, kine_id INT NOT NULL, date_affectation DATE NOT NULL, date_fin_contrat DATE DEFAULT NULL, salaire DOUBLE PRECISION NOT NULL, statut BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_197295188DC4BCC ON contrat_kine (kine_id)');
        $this->addSql('CREATE TABLE kine (id INT NOT NULL, equipe_id INT DEFAULT NULL, firstname VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, birthdate DATE NOT NULL, phone_number INT NOT NULL, email VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_4CC38C4F6D861B89 ON kine (equipe_id)');
        $this->addSql('ALTER TABLE contrat_kine ADD CONSTRAINT FK_197295188DC4BCC FOREIGN KEY (kine_id) REFERENCES kine (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE kine ADD CONSTRAINT FK_4CC38C4F6D861B89 FOREIGN KEY (equipe_id) REFERENCES equipe (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE contrat_kine_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE kine_id_seq CASCADE');
        $this->addSql('ALTER TABLE contrat_kine DROP CONSTRAINT FK_197295188DC4BCC');
        $this->addSql('ALTER TABLE kine DROP CONSTRAINT FK_4CC38C4F6D861B89');
        $this->addSql('DROP TABLE contrat_kine');
        $this->addSql('DROP TABLE kine');
    }
}
