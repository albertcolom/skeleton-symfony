<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210728164625 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE IF EXISTS bar');
        $this->addSql('DROP TABLE IF EXISTS foo');

        $this->addSql('CREATE TABLE foo(
            id BINARY(16) NOT NULL,
            name VARCHAR (255) NOT NULL,
            PRIMARY KEY(id)
        )
        DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`
        ENGINE = InnoDB');

        $this->addSql('CREATE TABLE bar(
            id BINARY(16) NOT NULL,
            foo_id BINARY(16) NOT NULL,
            name VARCHAR (255) NOT NULL,
            PRIMARY KEY(id),
            FOREIGN KEY (foo_id) REFERENCES foo(id)
        )
        DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`
        ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE IF EXISTS bar');
        $this->addSql('DROP TABLE IF EXISTS foo');
    }

    public function isTransactional(): bool
    {
        return false;
    }
}
