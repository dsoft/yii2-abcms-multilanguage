<?php
namespace abcms\multilanguage\migrations;

use yii\db\Migration;

/**
 * Class m200413_163849_i18n_init
 */
class m200413_163849_i18n_init extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%message_source}}', [
            'id' => $this->primaryKey(),
            'category' => $this->string(),
            'message' => $this->text(),
        ], $tableOptions);

        $this->createTable('{{%message_translation}}', [
            'id' => $this->integer()->notNull(),
            'language' => $this->string(16)->notNull(),
            'translation' => $this->text(),
        ], $tableOptions);

        $this->addPrimaryKey('pk_message_id_language', '{{%message_translation}}', ['id', 'language']);
        $onUpdateConstraint = 'RESTRICT';
        if ($this->db->driverName === 'sqlsrv') {
            // 'NO ACTION' is equivalent to 'RESTRICT' in MSSQL
            $onUpdateConstraint = 'NO ACTION';
        }
        $this->addForeignKey('fk_message_source_message', '{{%message_translation}}', 'id', '{{%message_source}}', 'id', 'CASCADE', $onUpdateConstraint);
        $this->createIndex('idx_source_message_category', '{{%message_source}}', 'category');
        $this->createIndex('idx_message_language', '{{%message_translation}}', 'language');
    }

    public function down()
    {
        $this->dropForeignKey('fk_message_source_message', '{{%message_translation}}');
        $this->dropTable('{{%message_translation}}');
        $this->dropTable('{{%message_source}}');
    }
}
