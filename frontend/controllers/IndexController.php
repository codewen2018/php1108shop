<?php

namespace frontend\controllers;

use backend\models\Category;
use backend\models\Goods;

class IndexController extends \yii\web\Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * 分类列表
     * @param $id 分类Id
     * @return string
     */
    public function actionList($id){

        //1.通过分类ID得到当前分类对象
        $cate=Category::findOne($id);
        //2.通过当前分类找出所有子孙分类
        $sonCates=Category::find()->where(['tree'=>$cate->tree])->andWhere(['>=','lft',$cate->lft])->andWhere("rgt<={$cate->rgt}")->asArray()->all();
        //3.通过当前二维数组提取成一维数组
        $cateIds=array_column($sonCates,'id');

      //  var_dump($cateIds);exit();
        //得到当前分类的所有商品
        //$goods=Goods::find()->where('category_id in (1,4,5,6)')->asArray()->all();
        $goods=Goods::find()->where(['in','category_id',$cateIds])->andWhere(['status'=>1])->orderBy('sort')->all();

       // var_dump($goods);exit;

        return $this->render('list',compact('goods'));
    }
    public function actionTest(){

        var_dump(\Yii::$app->controller->id."/".\Yii::$app->controller->action->id);

    }
}
