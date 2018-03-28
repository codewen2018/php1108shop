<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "address".
 *
 * @property int $id
 * @property int $user_id 用户ID
 * @property string $name 姓名
 * @property string $province 省份
 * @property string $city 市
 * @property string $county 区县
 * @property string $address 地址
 * @property string $mobile 手机号
 * @property int $status 状态:1 默认 0非默认
 */
class Address extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'address';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'status'], 'integer'],
            [['name', 'province', 'city', 'county', 'mobile'], 'string', 'max' => 20],
            [['address'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => '用户ID',
            'name' => '姓名',
            'province' => '省份',
            'city' => '市',
            'county' => '区县',
            'address' => '地址',
            'mobile' => '手机号',
            'status' => '状态:1 默认 0非默认',
        ];
    }
}
