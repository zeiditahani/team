<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250209162526 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE equipe_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE equipe (id INT NOT NULL, president_id INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, date_fondation DATE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_2449BA15B40A33C7 ON equipe (president_id)');
        $this->addSql('ALTER TABLE equipe ADD CONSTRAINT FK_2449BA15B40A33C7 FOREIGN KEY (president_id) REFERENCES president (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE entraineur ADD equipe_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE entraineur ADD CONSTRAINT FK_3D247E876D861B89 FOREIGN KEY (equipe_id) REFERENCES equipe (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_3D247E876D861B89 ON entraineur (equipe_id)');
        $this->addSql('ALTER TABLE joueur ADD equipe_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE joueur ADD CONSTRAINT FK_FD71A9C56D861B89 FOREIGN KEY (equipe_id) REFERENCES equipe (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_FD71A9C56D861B89 ON joueur (equipe_id)');
        $this->addSql('ALTER TABLE medecin ADD equipe_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE medecin ADD CONSTRAINT FK_1BDA53C66D861B89 FOREIGN KEY (equipe_id) REFERENCES equipe (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_1BDA53C66D861B89 ON medecin (equipe_id)');
        $this->addSql('ALTER TABLE photographe ADD equipe_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE photographe ADD CONSTRAINT FK_50DF4A8B6D861B89 FOREIGN KEY (equipe_id) REFERENCES equipe (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_50DF4A8B6D861B89 ON photographe (equipe_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE entraineur DROP CONSTRAINT FK_3D247E876D861B89');
        $this->addSql('ALTER TABLE joueur DROP CONSTRAINT FK_FD71A9C56D861B89');
        $this->addSql('ALTER TABLE medecin DROP CONSTRAINT FK_1BDA53C66D861B89');
        $this->addSql('ALTER TABLE photographe DROP CONSTRAINT FK_50DF4A8B6D861B89');
        $this->addSql('DROP SEQUENCE equipe_id_seq CASCADE');
        $this->addSql('ALTER TABLE equipe DROP CONSTRAINT FK_2449BA15B40A33C7');
        $this->addSql('DROP TABLE equipe');
        $this->addSql('DROP INDEX IDX_50DF4A8B6D861B89');
        $this->addSql('ALTER TABLE photographe DROP equipe_id');
        $this->addSql('DROP INDEX IDX_1BDA53C66D861B89');
        $this->addSql('ALTER TABLE medecin DROP equipe_id');
        $this->addSql('DROP INDEX IDX_FD71A9C56D861B89');
        $this->addSql('ALTER TABLE joueur DROP equipe_id');
        $this->addSql('DROP INDEX IDX_3D247E876D861B89');
        $this->addSql('ALTER TABLE entraineur DROP equipe_id');
    }
}
