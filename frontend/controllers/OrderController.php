<?php

namespace frontend\controllers;

class OrderController extends \yii\web\Controller
{
    public function actionIndex()
    {
        //判断有没有登录
        if (\Yii::$app->user->isGuest){

            return $this->redirect(['user/login','url'=>'/order/index']);


        }

        return $this->render('index');
    }

    public function actionAdd(){

        //必需要用事务

        //1.新增订单

        //2.循环商品再新增商品详情

        //3. 减商品库存





    }

}
