<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250310053038 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE equipes_adversaires_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE logistic_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE ticket_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE equipes_adversaires (id INT NOT NULL, nom VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_5ACD11086C6E55B5 ON equipes_adversaires (nom)');
        $this->addSql('CREATE TABLE logistic (id INT NOT NULL, match_id INT NOT NULL, type VARCHAR(255) NOT NULL, depense DOUBLE PRECISION NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_54BD53BD2ABEACD6 ON logistic (match_id)');
        $this->addSql('CREATE TABLE match_joueurs (matchs_id INT NOT NULL, joueur_id INT NOT NULL, PRIMARY KEY(matchs_id, joueur_id))');
        $this->addSql('CREATE INDEX IDX_875BD7F288EB7468 ON match_joueurs (matchs_id)');
        $this->addSql('CREATE INDEX IDX_875BD7F2A9E2D76C ON match_joueurs (joueur_id)');
        $this->addSql('CREATE TABLE ticket (id INT NOT NULL, match_id INT NOT NULL, type VARCHAR(50) NOT NULL, nb_ticket_dispo INT NOT NULL, nb_ticket_total INT NOT NULL, prix DOUBLE PRECISION NOT NULL, statut VARCHAR(20) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_97A0ADA32ABEACD6 ON ticket (match_id)');
        $this->addSql('ALTER TABLE logistic ADD CONSTRAINT FK_54BD53BD2ABEACD6 FOREIGN KEY (match_id) REFERENCES matchs (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE match_joueurs ADD CONSTRAINT FK_875BD7F288EB7468 FOREIGN KEY (matchs_id) REFERENCES matchs (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE match_joueurs ADD CONSTRAINT FK_875BD7F2A9E2D76C FOREIGN KEY (joueur_id) REFERENCES joueur (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE ticket ADD CONSTRAINT FK_97A0ADA32ABEACD6 FOREIGN KEY (match_id) REFERENCES matchs (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE matchs ADD equipe_adverse_id INT NOT NULL');
        $this->addSql('ALTER TABLE matchs ADD date DATE NOT NULL');
        $this->addSql('ALTER TABLE matchs ADD terrain VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE matchs ADD tactique VARCHAR(10) NOT NULL');
        $this->addSql('ALTER TABLE matchs ADD CONSTRAINT FK_6B1E604128C25BF2 FOREIGN KEY (equipe_adverse_id) REFERENCES equipes_adversaires (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_6B1E604128C25BF2 ON matchs (equipe_adverse_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE matchs DROP CONSTRAINT FK_6B1E604128C25BF2');
        $this->addSql('DROP SEQUENCE equipes_adversaires_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE logistic_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE ticket_id_seq CASCADE');
        $this->addSql('ALTER TABLE logistic DROP CONSTRAINT FK_54BD53BD2ABEACD6');
        $this->addSql('ALTER TABLE match_joueurs DROP CONSTRAINT FK_875BD7F288EB7468');
        $this->addSql('ALTER TABLE match_joueurs DROP CONSTRAINT FK_875BD7F2A9E2D76C');
        $this->addSql('ALTER TABLE ticket DROP CONSTRAINT FK_97A0ADA32ABEACD6');
        $this->addSql('DROP TABLE equipes_adversaires');
        $this->addSql('DROP TABLE logistic');
        $this->addSql('DROP TABLE match_joueurs');
        $this->addSql('DROP TABLE ticket');
        $this->addSql('DROP INDEX IDX_6B1E604128C25BF2');
        $this->addSql('ALTER TABLE matchs DROP equipe_adverse_id');
        $this->addSql('ALTER TABLE matchs DROP date');
        $this->addSql('ALTER TABLE matchs DROP terrain');
        $this->addSql('ALTER TABLE matchs DROP tactique');
    }
}
