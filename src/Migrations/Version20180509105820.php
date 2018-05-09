<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180509105820 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE post ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8DA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_5A8A6C8DA76ED395 ON post (user_id)');
        $this->addSql('ALTER TABLE user ADD team_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649296CD8AE FOREIGN KEY (team_id) REFERENCES team (id)');
        $this->addSql('CREATE INDEX IDX_8D93D649296CD8AE ON user (team_id)');
        $this->addSql('ALTER TABLE classification ADD team_id INT NOT NULL, ADD league_id INT NOT NULL');
        $this->addSql('ALTER TABLE classification ADD CONSTRAINT FK_456BD231296CD8AE FOREIGN KEY (team_id) REFERENCES team (id)');
        $this->addSql('ALTER TABLE classification ADD CONSTRAINT FK_456BD23158AFC4DE FOREIGN KEY (league_id) REFERENCES league (id)');
        $this->addSql('CREATE INDEX IDX_456BD231296CD8AE ON classification (team_id)');
        $this->addSql('CREATE INDEX IDX_456BD23158AFC4DE ON classification (league_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE classification DROP FOREIGN KEY FK_456BD231296CD8AE');
        $this->addSql('ALTER TABLE classification DROP FOREIGN KEY FK_456BD23158AFC4DE');
        $this->addSql('DROP INDEX IDX_456BD231296CD8AE ON classification');
        $this->addSql('DROP INDEX IDX_456BD23158AFC4DE ON classification');
        $this->addSql('ALTER TABLE classification DROP team_id, DROP league_id');
        $this->addSql('ALTER TABLE post DROP FOREIGN KEY FK_5A8A6C8DA76ED395');
        $this->addSql('DROP INDEX IDX_5A8A6C8DA76ED395 ON post');
        $this->addSql('ALTER TABLE post DROP user_id');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649296CD8AE');
        $this->addSql('DROP INDEX IDX_8D93D649296CD8AE ON user');
        $this->addSql('ALTER TABLE user DROP team_id');
    }
}
