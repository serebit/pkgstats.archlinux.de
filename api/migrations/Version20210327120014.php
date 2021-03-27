<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20210327120014 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() !== 'mysql',
            'Migration can only be executed safely on \'mysql\'.'
        );

        $this->addSql('LOCK TABLES country WRITE, mirror WRITE');

        $this->addSql('DROP INDEX country_month_code ON country');
        $this->addSql('ALTER TABLE country DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE country CHANGE code name VARCHAR(2) NOT NULL');
        $this->addSql('CREATE INDEX country_month_name ON country (month, name)');
        $this->addSql('ALTER TABLE country ADD PRIMARY KEY (name, month)');
        $this->addSql('DROP INDEX mirror_month_url ON mirror');
        $this->addSql('ALTER TABLE mirror DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE mirror CHANGE url name VARCHAR(191) NOT NULL');
        $this->addSql('CREATE INDEX mirror_month_name ON mirror (month, name)');
        $this->addSql('ALTER TABLE mirror ADD PRIMARY KEY (name, month)');

        $this->addSql('UNLOCK TABLES');
    }

    public function down(Schema $schema): void
    {
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() !== 'mysql',
            'Migration can only be executed safely on \'mysql\'.'
        );

        // phpcs:disable
        $this->addSql('DROP INDEX country_month_name ON country');
        $this->addSql('ALTER TABLE country DROP PRIMARY KEY');
        $this->addSql(
            'ALTER TABLE country CHANGE name code VARCHAR(2) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`'
        );
        $this->addSql('CREATE INDEX country_month_code ON country (month, code)');
        $this->addSql('ALTER TABLE country ADD PRIMARY KEY (code, month)');
        $this->addSql('DROP INDEX mirror_month_name ON mirror');
        $this->addSql('ALTER TABLE mirror DROP PRIMARY KEY');
        $this->addSql(
            'ALTER TABLE mirror CHANGE name url VARCHAR(191) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`'
        );
        $this->addSql('CREATE INDEX mirror_month_url ON mirror (month, url)');
        $this->addSql('ALTER TABLE mirror ADD PRIMARY KEY (url, month)');
        // phpcs:enable
    }
}
