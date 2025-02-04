<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250204061208 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6496BF700BD');
        $this->addSql('DROP INDEX UNIQ_8D93D6496BF700BD ON user');
        $this->addSql('ALTER TABLE user CHANGE status_id notification_type INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D64934E21C13 FOREIGN KEY (notification_type) REFERENCES constant (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D64934E21C13 ON user (notification_type)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D64934E21C13');
        $this->addSql('DROP INDEX UNIQ_8D93D64934E21C13 ON user');
        $this->addSql('ALTER TABLE user CHANGE notification_type status_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6496BF700BD FOREIGN KEY (status_id) REFERENCES constant (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D6496BF700BD ON user (status_id)');
    }
}
