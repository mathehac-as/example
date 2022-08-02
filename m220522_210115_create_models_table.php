<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%models}}`.
 */
class m220522_210115_create_models_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%models}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(),
            'series_id' => $this->integer()->notNull(),
        ]);
        
        $this->addForeignKey(
            'models_for_series_ibfk_1',
            '{{%models}}',
            ['series_id'],
            '{{%series}}',
            ['id'],
            'RESTRICT',
            'RESTRICT'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('models_for_series_ibfk_1', '{{%models}}');
        $this->dropTable('{{%models}}');
    }
}
