<?php

namespace frontend\controllers;

class IndexController extends \yii\web\Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionList($id){

        return $this->render('list');
    }
    public function actionTest(){

        var_dump(\Yii::$app->controller->id."/".\Yii::$app->controller->action->id);

    }
}
