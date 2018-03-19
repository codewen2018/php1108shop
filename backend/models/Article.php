<?php

namespace backend\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "article".
 *
 * @property int $id
 * @property string $title 标题
 * @property string $intro 简介
 * @property int $sort 排序
 * @property int $status 状态
 * @property int $cate_id 分类Id
 * @property int $create_time 创建时间
 * @property int $update_time 更新时间
 */
class Article extends \yii\db\ActiveRecord
{
    public function behaviors()
    {
        return [
            [

                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['create_time', 'update_time'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['update_time'],
                ],
                // if you're using datetime instead of UNIX timestamp:
                // 'value' => new Expression('NOW()'),
            ],
        ];
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title','sort','status','cate_id'], 'required'],
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
            'title' => '标题',
            'intro' => '简介',
            'sort' => '排序',
            'status' => '状态',
            'cate_id' => '分类Id',
            'create_time' => '创建时间',
            'update_time' => '更新时间',
        ];
    }

    //找到对应分类 1对1
    public function getCate(){

        return $this->hasOne(ArticleCategory::className(),['id'=>'cate_id']);


    }

}
