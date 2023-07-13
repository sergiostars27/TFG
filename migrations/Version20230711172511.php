<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230711172511 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE record DROP FOREIGN KEY FK_9B349F91A76ED395');
        $this->addSql('ALTER TABLE record DROP FOREIGN KEY FK_9B349F91E48FD905');
        $this->addSql('DROP TABLE record');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE record (id INT AUTO_INCREMENT NOT NULL, game_id INT NOT NULL, user_id INT NOT NULL, message VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, date DATE NOT NULL, INDEX IDX_9B349F91E48FD905 (game_id), INDEX IDX_9B349F91A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE record ADD CONSTRAINT FK_9B349F91A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE record ADD CONSTRAINT FK_9B349F91E48FD905 FOREIGN KEY (game_id) REFERENCES game (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
    }
}
