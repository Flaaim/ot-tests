<?php

declare(strict_types=1);

namespace App\Data\Migration;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20261006060739 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE notification_messages ALTER notification_id TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE notification_messages ALTER profile_id TYPE VARCHAR(255)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE notification_messages ALTER notification_id TYPE UUID');
        $this->addSql('ALTER TABLE notification_messages ALTER profile_id TYPE UUID');
    }
}
