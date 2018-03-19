<?php

namespace backend\models;

use backend\components\MenuQuery;
use creocoder\nestedsets\NestedSetsBehavior;
use Yii;

/**
 * This is the model class for table "category".
 *
 * @property int $id
 * @property int $tree
 * @property int $lft
 * @property int $rgt
 * @property int $depth 深度
 * @property string $name 名称
 * @property string $intro 简介
 * @property int $parent_id 父Id
 */
class Category extends \yii\db\ActiveRecord
{

    public function behaviors() {
        return [
            'tree' => [
                'class' => NestedSetsBehavior::className(),
                 'treeAttribute' => 'tree',
                // 'leftAttribute' => 'lft',
                // 'rightAttribute' => 'rgt',
                // 'depthAttribute' => 'depth',
            ],
        ];
    }

    public function transactions()
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_ALL,
        ];
    }

    public static function find()
    {
        return new MenuQuery(get_called_class());
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name','parent_id'], 'required'],//把那些没用的系统自动设置的东西给我干掉
            [['intro'],'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'tree' => 'Tree',
            'lft' => 'Lft',
            'rgt' => 'Rgt',
            'depth' => '深度',
            'name' => '名称',
            'intro' => '简介',
            'parent_id' => '父Id',
        ];
    }

    //得到层级结构的name
    public function getNameText(){


        return str_repeat("-",$this->depth*4).$this->name;


    }
}
