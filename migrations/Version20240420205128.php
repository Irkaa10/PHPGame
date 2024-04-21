<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240420205128 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE registration_user (registration_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_42ADC195833D8F43 (registration_id), INDEX IDX_42ADC195A76ED395 (user_id), PRIMARY KEY(registration_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE registration_user ADD CONSTRAINT FK_42ADC195833D8F43 FOREIGN KEY (registration_id) REFERENCES registration (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE registration_user ADD CONSTRAINT FK_42ADC195A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE registration ADD tournament_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE registration ADD CONSTRAINT FK_62A8A7A733D1A3E7 FOREIGN KEY (tournament_id) REFERENCES tournament (id)');
        $this->addSql('CREATE INDEX IDX_62A8A7A733D1A3E7 ON registration (tournament_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE registration_user DROP FOREIGN KEY FK_42ADC195833D8F43');
        $this->addSql('ALTER TABLE registration_user DROP FOREIGN KEY FK_42ADC195A76ED395');
        $this->addSql('DROP TABLE registration_user');
        $this->addSql('ALTER TABLE registration DROP FOREIGN KEY FK_62A8A7A733D1A3E7');
        $this->addSql('DROP INDEX IDX_62A8A7A733D1A3E7 ON registration');
        $this->addSql('ALTER TABLE registration DROP tournament_id');
    }
}
