<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240229121559 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE crypto (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, symbol VARCHAR(255) NOT NULL, price NUMERIC(10, 3) NOT NULL, price_history JSON NOT NULL COMMENT \'(DC2Type:json)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE transaction (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, cryptocurrency_id INT NOT NULL, date DATETIME NOT NULL, type VARCHAR(255) NOT NULL, quantity DOUBLE PRECISION NOT NULL, amount DOUBLE PRECISION NOT NULL, INDEX IDX_723705D1A76ED395 (user_id), INDEX IDX_723705D1583FC03A (cryptocurrency_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, has_wallet_id INT DEFAULT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), UNIQUE INDEX UNIQ_8D93D6495080B2BC (has_wallet_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE wallet (id INT AUTO_INCREMENT NOT NULL, transaction_id INT DEFAULT NULL, total_balance DOUBLE PRECISION NOT NULL, crypto_balance DOUBLE PRECISION NOT NULL, usable_balance DOUBLE PRECISION NOT NULL, bitcoin DOUBLE PRECISION NOT NULL, ethereum DOUBLE PRECISION NOT NULL, xrp DOUBLE PRECISION NOT NULL, cardano DOUBLE PRECISION NOT NULL, litecoin DOUBLE PRECISION NOT NULL, bitcoin_cash DOUBLE PRECISION NOT NULL, stellar DOUBLE PRECISION NOT NULL, iota DOUBLE PRECISION NOT NULL, dash DOUBLE PRECISION NOT NULL, nem DOUBLE PRECISION NOT NULL, INDEX IDX_7C68921F2FC0CB0F (transaction_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D1A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D1583FC03A FOREIGN KEY (cryptocurrency_id) REFERENCES crypto (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6495080B2BC FOREIGN KEY (has_wallet_id) REFERENCES wallet (id)');
        $this->addSql('ALTER TABLE wallet ADD CONSTRAINT FK_7C68921F2FC0CB0F FOREIGN KEY (transaction_id) REFERENCES transaction (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE transaction DROP FOREIGN KEY FK_723705D1A76ED395');
        $this->addSql('ALTER TABLE transaction DROP FOREIGN KEY FK_723705D1583FC03A');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6495080B2BC');
        $this->addSql('ALTER TABLE wallet DROP FOREIGN KEY FK_7C68921F2FC0CB0F');
        $this->addSql('DROP TABLE crypto');
        $this->addSql('DROP TABLE transaction');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE wallet');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
