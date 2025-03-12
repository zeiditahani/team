<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250311134023 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE analyses_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE analyses (id INT NOT NULL, match_id INT NOT NULL, joueur_id INT NOT NULL, nb_carton_rouge INT NOT NULL, nb_carton_jaune INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_AC86883C2ABEACD6 ON analyses (match_id)');
        $this->addSql('CREATE INDEX IDX_AC86883CA9E2D76C ON analyses (joueur_id)');
        $this->addSql('ALTER TABLE analyses ADD CONSTRAINT FK_AC86883C2ABEACD6 FOREIGN KEY (match_id) REFERENCES matchs (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE analyses ADD CONSTRAINT FK_AC86883CA9E2D76C FOREIGN KEY (joueur_id) REFERENCES joueur (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE analyses_id_seq CASCADE');
        $this->addSql('ALTER TABLE analyses DROP CONSTRAINT FK_AC86883C2ABEACD6');
        $this->addSql('ALTER TABLE analyses DROP CONSTRAINT FK_AC86883CA9E2D76C');
        $this->addSql('DROP TABLE analyses');
    }
}
