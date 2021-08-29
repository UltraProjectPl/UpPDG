<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20210828115634 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE offers (id UUID NOT NULL, creator_id UUID NOT NULL, title VARCHAR(255) NOT NULL, company_name VARCHAR(255) NOT NULL, remote_work_possible BOOLEAN NOT NULL, remote_work_only BOOLEAN NOT NULL, nip BOOLEAN DEFAULT NULL, tin BOOLEAN DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, deleted_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, payment_spreads_min_amount BIGINT DEFAULT NULL, payment_spreads_min_currency CHAR(3) DEFAULT NULL, payment_spreads_max_amount BIGINT DEFAULT NULL, payment_spreads_max_currency CHAR(3) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_DA46042761220EA6 ON offers (creator_id)');
        $this->addSql('COMMENT ON COLUMN offers.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN offers.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN offers.deleted_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN offers.payment_spreads_min_currency IS \'(DC2Type:currency)\'');
        $this->addSql('COMMENT ON COLUMN offers.payment_spreads_max_currency IS \'(DC2Type:currency)\'');
        $this->addSql('ALTER TABLE offers ADD CONSTRAINT FK_DA46042761220EA6 FOREIGN KEY (creator_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE sessions ALTER id TYPE UUID');
        $this->addSql('ALTER TABLE sessions ALTER id DROP DEFAULT');
        $this->addSql('ALTER TABLE sessions ALTER user_id TYPE UUID');
        $this->addSql('ALTER TABLE sessions ALTER user_id DROP DEFAULT');
        $this->addSql('ALTER TABLE users ALTER id TYPE UUID');
        $this->addSql('ALTER TABLE users ALTER id DROP DEFAULT');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP TABLE offers');
        $this->addSql('ALTER TABLE users ALTER id TYPE UUID');
        $this->addSql('ALTER TABLE users ALTER id DROP DEFAULT');
        $this->addSql('ALTER TABLE sessions ALTER id TYPE UUID');
        $this->addSql('ALTER TABLE sessions ALTER id DROP DEFAULT');
        $this->addSql('ALTER TABLE sessions ALTER user_id TYPE UUID');
        $this->addSql('ALTER TABLE sessions ALTER user_id DROP DEFAULT');
    }
}
