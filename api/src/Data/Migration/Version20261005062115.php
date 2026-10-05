<?php

declare(strict_types=1);

namespace App\Data\Migration;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20261005062115 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE notification_messages (status VARCHAR(8) NOT NULL, message_id VARCHAR NOT NULL, notification_id UUID NOT NULL, profile_id UUID NOT NULL, PRIMARY KEY (message_id))');
        $this->addSql('CREATE TABLE notifications (status VARCHAR NOT NULL, notification_id VARCHAR NOT NULL, subject VARCHAR(100) NOT NULL, message TEXT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY (notification_id))');
        $this->addSql('ALTER TABLE tests ALTER normative_docs DROP DEFAULT');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE notification_messages');
        $this->addSql('DROP TABLE notifications');
        $this->addSql('ALTER TABLE tests ALTER normative_docs SET DEFAULT \'[]\'');
    }
}
