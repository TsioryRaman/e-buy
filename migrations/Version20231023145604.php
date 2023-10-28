<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231023145604 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE article_view (id INT AUTO_INCREMENT NOT NULL, view_by_id INT DEFAULT NULL, article_id INT DEFAULT NULL, INDEX IDX_4EBF2C8FC8C48717 (view_by_id), INDEX IDX_4EBF2C8F7294869C (article_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE article_view ADD CONSTRAINT FK_4EBF2C8FC8C48717 FOREIGN KEY (view_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE article_view ADD CONSTRAINT FK_4EBF2C8F7294869C FOREIGN KEY (article_id) REFERENCES article (id)');
        $this->addSql('ALTER TABLE user ADD roles LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE article_view DROP FOREIGN KEY FK_4EBF2C8FC8C48717');
        $this->addSql('ALTER TABLE article_view DROP FOREIGN KEY FK_4EBF2C8F7294869C');
        $this->addSql('DROP TABLE article_view');
        $this->addSql('ALTER TABLE user DROP roles');
    }
}
