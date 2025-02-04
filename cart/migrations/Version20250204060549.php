<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250204060549 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('
            INSERT INTO constant (id, type_id, type, name, value)
            VALUES (201, 200, "NOTIFICATION_TYPE", "NOTIFICATION_TYPE_SMS", "sms"),
            (202, 200, "NOTIFICATION_TYPE", "NOTIFICATION_TYPE_EMAIL", "email")
        ');

    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DELETE FROM constant WHERE id IN (201, 202)');

    }
}
