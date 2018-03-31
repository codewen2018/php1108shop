<?php

use yii\db\Migration;

/**
 * Handles the creation of table `cart`.
 */
class m180331_013813_create_cart_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('cart', [
            'id' => $this->primaryKey(),
            'goods_id'=>$this->integer()->comment('商品Id'),
            'num'=>$this->integer()->comment('数量'),
            'user_id'=>$this->integer()->comment('用户Id'),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('cart');
    }
}
