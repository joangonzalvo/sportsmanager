<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180509105033 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE league CHANGE id id INT AUTO_INCREMENT NOT NULL, CHANGE name name VARCHAR(45) NOT NULL, CHANGE type type VARCHAR(45) NOT NULL, CHANGE date_start date_start DATETIME NOT NULL');
        $this->addSql('ALTER TABLE post CHANGE id id INT AUTO_INCREMENT NOT NULL, CHANGE title title VARCHAR(45) NOT NULL, CHANGE content content LONGTEXT NOT NULL, CHANGE category category VARCHAR(45) NOT NULL, CHANGE create_date create_date DATETIME NOT NULL');
        $this->addSql('ALTER TABLE user ADD team_role VARCHAR(45) NOT NULL, DROP teamRole, CHANGE id id INT AUTO_INCREMENT NOT NULL, CHANGE username username VARCHAR(45) NOT NULL, CHANGE password password VARCHAR(90) NOT NULL, CHANGE role role VARCHAR(45) NOT NULL, CHANGE email email VARCHAR(45) NOT NULL');
        $this->addSql('ALTER TABLE team ADD league_titles INT NOT NULL, ADD other_titles INT NOT NULL, ADD team_value INT NOT NULL, DROP leagueTitles, DROP otherTitles, DROP teamValue, CHANGE id id INT AUTO_INCREMENT NOT NULL, CHANGE teamname teamname VARCHAR(45) NOT NULL, CHANGE logo logo VARCHAR(45) NOT NULL, CHANGE teamkey teamkey VARCHAR(90) NOT NULL');
        $this->addSql('ALTER TABLE classification CHANGE id id INT AUTO_INCREMENT NOT NULL, CHANGE points points INT NOT NULL, CHANGE win win INT NOT NULL, CHANGE lost lost INT NOT NULL, CHANGE draw draw INT NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE classification CHANGE id id INT NOT NULL, CHANGE points points INT DEFAULT NULL, CHANGE win win INT DEFAULT NULL, CHANGE lost lost INT DEFAULT NULL, CHANGE draw draw INT DEFAULT NULL');
        $this->addSql('ALTER TABLE league CHANGE id id INT NOT NULL, CHANGE name name VARCHAR(45) DEFAULT NULL COLLATE latin1_swedish_ci, CHANGE type type VARCHAR(45) DEFAULT NULL COLLATE latin1_swedish_ci, CHANGE date_start date_start DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL');
        $this->addSql('ALTER TABLE post CHANGE id id INT NOT NULL, CHANGE title title VARCHAR(45) DEFAULT NULL COLLATE latin1_swedish_ci, CHANGE content content TEXT DEFAULT NULL COLLATE latin1_swedish_ci, CHANGE category category VARCHAR(45) DEFAULT NULL COLLATE latin1_swedish_ci, CHANGE create_date create_date DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL');
        $this->addSql('ALTER TABLE team ADD leagueTitles INT DEFAULT NULL, ADD otherTitles INT DEFAULT NULL, ADD teamValue VARCHAR(45) DEFAULT NULL COLLATE latin1_swedish_ci, DROP league_titles, DROP other_titles, DROP team_value, CHANGE id id INT NOT NULL, CHANGE teamname teamname VARCHAR(45) DEFAULT NULL COLLATE latin1_swedish_ci, CHANGE logo logo VARCHAR(45) DEFAULT NULL COLLATE latin1_swedish_ci, CHANGE teamkey teamkey VARCHAR(90) DEFAULT NULL COLLATE latin1_swedish_ci');
        $this->addSql('ALTER TABLE user ADD teamRole VARCHAR(45) DEFAULT NULL COLLATE latin1_swedish_ci, DROP team_role, CHANGE id id INT NOT NULL, CHANGE username username VARCHAR(45) DEFAULT NULL COLLATE latin1_swedish_ci, CHANGE password password VARCHAR(90) DEFAULT NULL COLLATE latin1_swedish_ci, CHANGE role role VARCHAR(45) DEFAULT NULL COLLATE latin1_swedish_ci, CHANGE email email VARCHAR(45) DEFAULT NULL COLLATE latin1_swedish_ci');
    }
}
