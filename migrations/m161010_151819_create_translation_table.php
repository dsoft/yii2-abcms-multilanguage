<?php

use yii\db\Migration;

/**
 * Handles the creation for table `translation`.
 * ./yii migrate --migrationPath=@vendor/abcms/yii2-multilanguage/migrations
 */
class m161010_151819_create_translation_table extends Migration
{

    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('translation', [
            'id' => $this->primaryKey(),
            'modelId' => $this->integer()->notNull(),
            'pk' => $this->integer()->notNull(),
            'attribute' => $this->string()->notNull(),
            'lang' => $this->string(40)->notNull(),
            'translation' => $this->text(),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('translation');
    }

}
