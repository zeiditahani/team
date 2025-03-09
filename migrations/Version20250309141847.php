<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250309141847 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE logistic ADD match_id INT NOT NULL');
        $this->addSql('ALTER TABLE logistic ADD CONSTRAINT FK_54BD53BD2ABEACD6 FOREIGN KEY (match_id) REFERENCES matchs (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_54BD53BD2ABEACD6 ON logistic (match_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE logistic DROP CONSTRAINT FK_54BD53BD2ABEACD6');
        $this->addSql('DROP INDEX IDX_54BD53BD2ABEACD6');
        $this->addSql('ALTER TABLE logistic DROP match_id');
    }
}
