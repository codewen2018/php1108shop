<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "brand".
 *
 * @property int $id
 * @property string $name 名称
 * @property string $logo 图像
 * @property int $sort 排序
 * @property int $status 状态
 * @property string $intro 简介
 */
class Brand extends \yii\db\ActiveRecord
{

    //设置一个图片属性
    public $img;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name','sort','status'], 'required'],
            [['img'],'image','skipOnEmpty' => true,'extensions' => 'png,jpg,gif'],
            [['intro'], 'safe'],

        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '名称',
            'logo' => '图像',
            'sort' => '排序',
            'status' => '状态',
            'intro' => '简介',
        ];
    }
}
