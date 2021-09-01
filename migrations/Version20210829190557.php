<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20210829190557 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE offers ADD city VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE offers ALTER id TYPE UUID');
        $this->addSql('ALTER TABLE offers ALTER id DROP DEFAULT');
        $this->addSql('ALTER TABLE offers ALTER creator_id TYPE UUID');
        $this->addSql('ALTER TABLE offers ALTER creator_id DROP DEFAULT');
        $this->addSql('ALTER TABLE sessions ALTER id TYPE UUID');
        $this->addSql('ALTER TABLE sessions ALTER id DROP DEFAULT');
        $this->addSql('ALTER TABLE sessions ALTER user_id TYPE UUID');
        $this->addSql('ALTER TABLE sessions ALTER user_id DROP DEFAULT');
        $this->addSql('ALTER TABLE users ADD slug VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE users ALTER id TYPE UUID');
        $this->addSql('ALTER TABLE users ALTER id DROP DEFAULT');
        $this->addSql('ALTER TABLE users ALTER updated_at DROP NOT NULL');
        $this->addSql('ALTER TABLE users ALTER deleted_at DROP NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1483A5E9989D9B62 ON users (slug)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP INDEX UNIQ_1483A5E9989D9B62');
        $this->addSql('ALTER TABLE users DROP slug');
        $this->addSql('ALTER TABLE users ALTER id TYPE UUID');
        $this->addSql('ALTER TABLE users ALTER id DROP DEFAULT');
        $this->addSql('ALTER TABLE users ALTER updated_at SET NOT NULL');
        $this->addSql('ALTER TABLE users ALTER deleted_at SET NOT NULL');
        $this->addSql('ALTER TABLE sessions ALTER id TYPE UUID');
        $this->addSql('ALTER TABLE sessions ALTER id DROP DEFAULT');
        $this->addSql('ALTER TABLE sessions ALTER user_id TYPE UUID');
        $this->addSql('ALTER TABLE sessions ALTER user_id DROP DEFAULT');
        $this->addSql('ALTER TABLE offers DROP city');
        $this->addSql('ALTER TABLE offers ALTER id TYPE UUID');
        $this->addSql('ALTER TABLE offers ALTER id DROP DEFAULT');
        $this->addSql('ALTER TABLE offers ALTER creator_id TYPE UUID');
        $this->addSql('ALTER TABLE offers ALTER creator_id DROP DEFAULT');
    }
}
