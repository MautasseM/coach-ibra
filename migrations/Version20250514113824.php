<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250514113824 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE subscription ADD plan_id INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE subscription ADD CONSTRAINT FK_A3C664D3E899029B FOREIGN KEY (plan_id) REFERENCES subscription_plan (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_A3C664D3E899029B ON subscription (plan_id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE subscription DROP FOREIGN KEY FK_A3C664D3E899029B
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_A3C664D3E899029B ON subscription
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE subscription DROP plan_id
        SQL);
    }
}
