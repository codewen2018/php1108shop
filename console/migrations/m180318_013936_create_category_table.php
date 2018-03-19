<?php

use yii\db\Migration;

/**
 * Handles the creation of table `category`.
 */
class m180318_013936_create_category_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('category', [
            'id' => $this->primaryKey(),
            'tree' => $this->integer()->notNull(),
            'lft' => $this->integer()->notNull(),
            'rgt' => $this->integer()->notNull(),
            'depth' => $this->integer()->notNull()->comment('深度'),
            'name' => $this->string()->notNull()->comment('名称'),
            'intro' => $this->string()->notNull()->comment('简介'),
            'parent_id' => $this->integer()->notNull()->defaultValue(0)->comment('父Id'),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('category');
    }
}
