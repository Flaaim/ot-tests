<?php

declare(strict_types=1);

namespace App\Data\Migration;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260824063421 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE answers (id VARCHAR NOT NULL, question_id VARCHAR(255) NOT NULL, selected_answers_ids JSONB NOT NULL, is_correct BOOLEAN NOT NULL, attempt_id VARCHAR NOT NULL, PRIMARY KEY (id))');
        $this->addSql('CREATE INDEX IDX_50D0C606B191BE6B ON answers (attempt_id)');
        $this->addSql('ALTER TABLE answers ADD CONSTRAINT FK_50D0C606B191BE6B FOREIGN KEY (attempt_id) REFERENCES attempts (id) ON DELETE CASCADE NOT DEFERRABLE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE answers DROP CONSTRAINT FK_50D0C606B191BE6B');
        $this->addSql('DROP TABLE answers');
    }
}
