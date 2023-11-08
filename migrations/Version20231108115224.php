<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231108115224 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE article_view DROP INDEX UNIQ_4EBF2C8F7294869C, ADD INDEX IDX_4EBF2C8F7294869C (article_id)');
        $this->addSql('ALTER TABLE article_view DROP INDEX UNIQ_4EBF2C8FC8C48717, ADD INDEX IDX_4EBF2C8FC8C48717 (view_by_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE article_view DROP INDEX IDX_4EBF2C8FC8C48717, ADD UNIQUE INDEX UNIQ_4EBF2C8FC8C48717 (view_by_id)');
        $this->addSql('ALTER TABLE article_view DROP INDEX IDX_4EBF2C8F7294869C, ADD UNIQUE INDEX UNIQ_4EBF2C8F7294869C (article_id)');
    }
}
