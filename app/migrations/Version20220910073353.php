<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220910073353 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE quiz_session_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE quiz_session (id INT NOT NULL, participant_id INT DEFAULT NULL, finished BOOLEAN NOT NULL, uuid VARCHAR(80) NOT NULL, score INT NOT NULL, unanswered INT NOT NULL, start_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, end_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C21E7874D17F50A6 ON quiz_session (uuid)');
        $this->addSql('CREATE INDEX IDX_C21E78749D1C3019 ON quiz_session (participant_id)');
        $this->addSql('COMMENT ON COLUMN quiz_session.start_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN quiz_session.end_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE quiz_session ADD CONSTRAINT FK_C21E78749D1C3019 FOREIGN KEY (participant_id) REFERENCES participant (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE quiz_session_id_seq CASCADE');
        $this->addSql('ALTER TABLE quiz_session DROP CONSTRAINT FK_C21E78749D1C3019');
        $this->addSql('DROP TABLE quiz_session');
    }
}
