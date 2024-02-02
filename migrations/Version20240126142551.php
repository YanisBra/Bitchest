<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240126142551 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE wallet (id INT AUTO_INCREMENT NOT NULL, total_balance DOUBLE PRECISION NOT NULL, crypto_balance DOUBLE PRECISION NOT NULL, usable_balance DOUBLE PRECISION NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE users DROP FOREIGN KEY FK_1483A5E977E1607F');
        $this->addSql('ALTER TABLE users DROP FOREIGN KEY FK_1483A5E9C3B43BA3');
        $this->addSql('DROP TABLE users');
        $this->addSql('ALTER TABLE user ADD has_wallet_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6495080B2BC FOREIGN KEY (has_wallet_id) REFERENCES wallet (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D6495080B2BC ON user (has_wallet_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6495080B2BC');
        $this->addSql('CREATE TABLE users (id INT AUTO_INCREMENT NOT NULL, wallets_id INT DEFAULT NULL, transactions_id INT DEFAULT NULL, role VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, username VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, password VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, INDEX IDX_1483A5E9C3B43BA3 (wallets_id), INDEX IDX_1483A5E977E1607F (transactions_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE users ADD CONSTRAINT FK_1483A5E977E1607F FOREIGN KEY (transactions_id) REFERENCES users (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE users ADD CONSTRAINT FK_1483A5E9C3B43BA3 FOREIGN KEY (wallets_id) REFERENCES users (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('DROP TABLE wallet');
        $this->addSql('DROP INDEX UNIQ_8D93D6495080B2BC ON user');
        $this->addSql('ALTER TABLE user DROP has_wallet_id');
    }
}
