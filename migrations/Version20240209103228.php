<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240209103228 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE wallet ADD bitcoin DOUBLE PRECISION NOT NULL, ADD ethereum DOUBLE PRECISION NOT NULL, ADD xrp DOUBLE PRECISION NOT NULL, ADD cardano DOUBLE PRECISION NOT NULL, ADD litecoin DOUBLE PRECISION NOT NULL, ADD bitcoin_cash DOUBLE PRECISION NOT NULL, ADD stellar DOUBLE PRECISION NOT NULL, ADD iota DOUBLE PRECISION NOT NULL, ADD dash DOUBLE PRECISION NOT NULL, ADD nem DOUBLE PRECISION NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE wallet DROP bitcoin, DROP ethereum, DROP xrp, DROP cardano, DROP litecoin, DROP bitcoin_cash, DROP stellar, DROP iota, DROP dash, DROP nem');
    }
}
