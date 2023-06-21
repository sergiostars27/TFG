<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230619103524 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE attributes (id INT AUTO_INCREMENT NOT NULL, ficha_id INT NOT NULL, name VARCHAR(500) NOT NULL, attribute VARCHAR(1000) DEFAULT NULL, INDEX IDX_319B9E705030B25F (ficha_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ficha (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, game_id INT NOT NULL, character_name VARCHAR(255) NOT NULL, age VARCHAR(255) DEFAULT NULL, description VARCHAR(1000) DEFAULT NULL, INDEX IDX_4B7E0861A76ED395 (user_id), INDEX IDX_4B7E0861E48FD905 (game_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE attributes ADD CONSTRAINT FK_319B9E705030B25F FOREIGN KEY (ficha_id) REFERENCES ficha (id)');
        $this->addSql('ALTER TABLE ficha ADD CONSTRAINT FK_4B7E0861A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE ficha ADD CONSTRAINT FK_4B7E0861E48FD905 FOREIGN KEY (game_id) REFERENCES game (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE attributes DROP FOREIGN KEY FK_319B9E705030B25F');
        $this->addSql('ALTER TABLE ficha DROP FOREIGN KEY FK_4B7E0861A76ED395');
        $this->addSql('ALTER TABLE ficha DROP FOREIGN KEY FK_4B7E0861E48FD905');
        $this->addSql('DROP TABLE attributes');
        $this->addSql('DROP TABLE ficha');
    }
}
