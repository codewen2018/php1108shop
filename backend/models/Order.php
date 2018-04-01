<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "order".
 *
 * @property int $id
 * @property int $user_id
 * @property string $name
 * @property string $province
 * @property string $city
 * @property string $area
 * @property string $detail_address
 * @property string $tel
 * @property int $delivery_id
 * @property string $delivery_name
 * @property string $delivery_price
 * @property int $payment_id
 * @property string $payment_name
 * @property string $price
 * @property int $status
 * @property string $trade_no
 * @property int $create_time
 */
class Order extends \yii\db\ActiveRecord
{




    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'name' => 'Name',
            'province' => 'Province',
            'city' => 'City',
            'area' => 'Area',
            'detail_address' => 'Detail Address',
            'tel' => 'Tel',
            'delivery_id' => 'Delivery ID',
            'delivery_name' => 'Delivery Name',
            'delivery_price' => 'Delivery Price',
            'payment_id' => 'Payment ID',
            'payment_name' => 'Payment Name',
            'price' => 'Price',
            'status' => 'Status',
            'trade_no' => 'Trade No',
            'create_time' => 'Create Time',
        ];
    }
}
