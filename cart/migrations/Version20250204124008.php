<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250204124008 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `order` RENAME INDEX fk_f52993986bf700ba TO IDX_F52993986BF700BD');
        $this->addSql('ALTER TABLE user RENAME INDEX fk_8d93d649d0520629 TO IDX_8D93D649D0520624');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user RENAME INDEX idx_8d93d649d0520624 TO FK_8D93D649D0520629');
        $this->addSql('ALTER TABLE `order` RENAME INDEX idx_f52993986bf700bd TO FK_F52993986BF700BA');
    }
}
