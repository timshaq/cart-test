<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250202113605 extends AbstractMigration
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
            VALUES (101, 100, "ORDER_STATUS", "ORDER_STATUS_PAID", "оплачен и ждёт сборки"),
            (102, 100, "ORDER_STATUS", "ORDER_STATUS_ASSEMBLY", "в сборке"),
            (103, 100, "ORDER_STATUS", "ORDER_STATUS_DELIVERY", "готов к выдаче/доставляется"),
            (104, 100, "ORDER_STATUS", "ORDER_STATUS_COMPLETED", "получен или отменён")
        ');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DELETE FROM constant WHERE id IN (101, 102, 103, 104)');
    }
}
