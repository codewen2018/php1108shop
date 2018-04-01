<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "order_detail".
 *
 * @property int $id
 * @property int $order_id
 * @property int $goods_id
 * @property int $amount
 * @property string $goods_name
 * @property string $logo
 * @property string $price
 * @property string $total_price
 */
class OrderDetail extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'order_id' => 'Order ID',
            'goods_id' => 'Goods ID',
            'amount' => 'Amount',
            'goods_name' => 'Goods Name',
            'logo' => 'Logo',
            'price' => 'Price',
            'total_price' => 'Total Price',
        ];
    }
}
