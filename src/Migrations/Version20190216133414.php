<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190216133414 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE fos_user_user_group DROP FOREIGN KEY FK_B3C77447FE54D947');
        $this->addSql('DROP TABLE fos_group');
        $this->addSql('DROP TABLE fos_user_user_group');
        $this->addSql('DROP INDEX medDepotlegal ON medicament');
        $this->addSql('DROP INDEX medDepotlegal_2 ON medicament');
        $this->addSql('ALTER TABLE medicament DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE medicament ADD id INT AUTO_INCREMENT NOT NULL, ADD famille_id INT DEFAULT NULL, DROP famCode, CHANGE medDepotlegal medDepotlegal VARCHAR(10) DEFAULT NULL');
        $this->addSql('ALTER TABLE medicament ADD CONSTRAINT FK_9A9C723A97A77B84 FOREIGN KEY (famille_id) REFERENCES famille (id)');
        $this->addSql('CREATE INDEX IDX_9A9C723A97A77B84 ON medicament (famille_id)');
        $this->addSql('ALTER TABLE medicament ADD PRIMARY KEY (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE fos_group (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(180) NOT NULL COLLATE utf8mb4_unicode_ci, roles LONGTEXT NOT NULL COLLATE utf8mb4_unicode_ci COMMENT \'(DC2Type:array)\', UNIQUE INDEX UNIQ_4B019DDB5E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE fos_user_user_group (user_id INT NOT NULL, group_id INT NOT NULL, INDEX IDX_B3C77447A76ED395 (user_id), INDEX IDX_B3C77447FE54D947 (group_id), PRIMARY KEY(user_id, group_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE fos_user_user_group ADD CONSTRAINT FK_B3C77447A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE fos_user_user_group ADD CONSTRAINT FK_B3C77447FE54D947 FOREIGN KEY (group_id) REFERENCES fos_group (id)');
        $this->addSql('ALTER TABLE medicament MODIFY id INT NOT NULL');
        $this->addSql('ALTER TABLE medicament DROP FOREIGN KEY FK_9A9C723A97A77B84');
        $this->addSql('DROP INDEX IDX_9A9C723A97A77B84 ON medicament');
        $this->addSql('ALTER TABLE medicament DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE medicament ADD famCode VARCHAR(3) NOT NULL COLLATE utf8_general_ci, DROP id, DROP famille_id, CHANGE medDepotlegal medDepotlegal VARCHAR(10) NOT NULL COLLATE utf8_general_ci');
        $this->addSql('CREATE INDEX FK_{413a5d7485a845e7b320df5e9396baed} ON medicament (famCode)');
        $this->addSql('CREATE INDEX medDepotlegal ON medicament (medDepotlegal)');
        $this->addSql('CREATE INDEX medDepotlegal_2 ON medicament (medDepotlegal)');
        $this->addSql('ALTER TABLE medicament ADD PRIMARY KEY (medDepotlegal)');
    }
}
