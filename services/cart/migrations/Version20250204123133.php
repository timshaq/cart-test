<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250204123133 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F52993986BF700BD');
        $this->addSql('DROP INDEX UNIQ_F52993986BF700BD ON `order`');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F52993986BF700BA FOREIGN KEY (status_id) REFERENCES constant (id)');

    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F52993986BF700BD FOREIGN KEY (status_id) REFERENCES constant (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_F52993986BF700BD ON `order` (status_id)');
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F52993986BF700BA');

    }
}
