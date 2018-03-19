<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/18
 * Time: 9:48
 */

namespace backend\components;


use creocoder\nestedsets\NestedSetsQueryBehavior;

class MenuQuery extends \yii\db\ActiveQuery
{
    public function behaviors() {
        return [
            NestedSetsQueryBehavior::className(),
        ];
    }
}