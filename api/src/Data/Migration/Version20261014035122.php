<?php

declare(strict_types=1);

namespace App\Data\Migration;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20261014035122 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE attempts ALTER test_id TYPE UUID USING test_id::uuid');
        $this->addSql('ALTER TABLE attempts ALTER user_id TYPE UUID USING user_id::uuid');
        $this->addSql('ALTER TABLE tests ALTER category_id TYPE UUID USING category_id::uuid');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE attempts ALTER test_id TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE attempts ALTER user_id TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE notifications ALTER type SET DEFAULT \'admin\'');
        $this->addSql('ALTER TABLE tests ALTER category_id TYPE VARCHAR(255)');
    }
}
