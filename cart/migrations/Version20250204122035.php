<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250204122035 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649D0520624');
        $this->addSql('DROP INDEX UNIQ_8D93D649D0520624 ON user');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649D0520629 FOREIGN KEY (notification_type_id) REFERENCES constant (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649D0520624 FOREIGN KEY (notification_type_id) REFERENCES constant (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649D0520624 ON user (notification_type_id)');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649D0520629');
    }
}
