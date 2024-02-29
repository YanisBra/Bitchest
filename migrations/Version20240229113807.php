<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240229113807 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE coin_price_history (id INT AUTO_INCREMENT NOT NULL, coin_uuid VARCHAR(255) NOT NULL, price NUMERIC(20, 10) NOT NULL, timestamp INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE crypto_price_history (id INT AUTO_INCREMENT NOT NULL, coin_uuid VARCHAR(255) NOT NULL, price NUMERIC(20, 10) NOT NULL, timestamp INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE crypto ADD price_history JSON NOT NULL, CHANGE price price NUMERIC(10, 3) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE coin_price_history');
        $this->addSql('DROP TABLE crypto_price_history');
        $this->addSql('ALTER TABLE crypto DROP price_history, CHANGE price price DOUBLE PRECISION NOT NULL');
    }
}
