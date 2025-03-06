<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250306154158 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP SEQUENCE training_session_id_seq CASCADE');
        $this->addSql('CREATE SEQUENCE fan_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE fan_revenue_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE trainingsession_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE fan (id INT NOT NULL, firstname VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, numero_telephone INT NOT NULL, service_fourni VARCHAR(255) NOT NULL, datedeb DATE NOT NULL, datefin DATE NOT NULL, prix DOUBLE PRECISION NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE fan_revenue (id INT NOT NULL, fan_id INT NOT NULL, revenue_obtenu_fan DOUBLE PRECISION NOT NULL, date_encaissement DATE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_81198A7E89C48F0B ON fan_revenue (fan_id)');
        $this->addSql('CREATE TABLE task (id INT NOT NULL, nom VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, duree TIME(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE trainingsession (id INT NOT NULL, date DATE NOT NULL, time TIME(0) WITHOUT TIME ZONE NOT NULL, tasks JSON NOT NULL, joueurs JSON NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE fan_revenue ADD CONSTRAINT FK_81198A7E89C48F0B FOREIGN KEY (fan_id) REFERENCES fan (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE fan_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE fan_revenue_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE trainingsession_id_seq CASCADE');
        $this->addSql('CREATE SEQUENCE training_session_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('ALTER TABLE fan_revenue DROP CONSTRAINT FK_81198A7E89C48F0B');
        $this->addSql('DROP TABLE fan');
        $this->addSql('DROP TABLE fan_revenue');
        $this->addSql('DROP TABLE task');
        $this->addSql('DROP TABLE trainingsession');
    }
}
