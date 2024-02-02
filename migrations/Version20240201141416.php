<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240201141416 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE crypto');
        $this->addSql('ALTER TABLE transaction ADD user_id INT DEFAULT NULL, CHANGE transaction_date date DATETIME NOT NULL, CHANGE transaction_type type VARCHAR(255) NOT NULL, CHANGE transaction_amount amount DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D1A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_723705D1A76ED395 ON transaction (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE crypto (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE transaction DROP FOREIGN KEY FK_723705D1A76ED395');
        $this->addSql('DROP INDEX UNIQ_723705D1A76ED395 ON transaction');
        $this->addSql('ALTER TABLE transaction DROP user_id, CHANGE type transaction_type VARCHAR(255) NOT NULL, CHANGE amount transaction_amount DOUBLE PRECISION NOT NULL, CHANGE date transaction_date DATETIME NOT NULL');
    }
}
