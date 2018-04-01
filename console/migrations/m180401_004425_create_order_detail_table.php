<?php

use yii\db\Migration;

/**
 * Handles the creation of table `order_detail`.
 */
class m180401_004425_create_order_detail_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('order_detail', [
            'id' => $this->primaryKey(),
            'order_id'=>$this->integer(),
            'goods_id'=>$this->integer(),
            'amount'=>$this->integer(),
            'goods_name'=>$this->string(20),
            'logo'=>$this->string(20),
            'price'=>$this->decimal(10,2),
            'total_price'=>$this->decimal(10,2),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('order_detail');
    }
}
