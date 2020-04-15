<?php
namespace abcms\multilanguage\migrations;

use yii\db\Migration;
use abcms\multilanguage\models\Language;

/**
 * Handles the creation of table `{{%language}}`.
 */
class m200415_152253_create_language_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%language}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'code' => $this->string()->notNull(),
            'direction' => $this->string()->null(),
            'active' => $this->boolean()->notNull()->defaultValue(1),
            'ordering' => $this->integer()->notNull()->defaultValue(1),
        ]);
        $this->insert('{{%language}}', ['name' => 'English', 'code' => 'en', 'direction' => Language::DIRECTION_LTR]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%language}}');
    }
}
