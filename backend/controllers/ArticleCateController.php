<?php

namespace backend\controllers;

use backend\models\ArticleCategory;

class ArticleCateController extends \yii\web\Controller
{
    /**
     * 文章分类列表
     * @return string
     */
    public function actionIndex()
    {
        //找出所有文章分类
        $cates=ArticleCategory::find()->all();
        return $this->render('index',compact('cates'));
    }
    public function actionAdd(){
        //创建一个数据模型对象
        $model=new ArticleCategory();
        //判断Post
        $request=\Yii::$app->request;
        if ($request->isPost){
            //数据绑定
            $model->load($request->post());

            //后台验证
            if ($model->validate()) {

                //保存数据
                if ($model->save()) {
                    //提示
                    \Yii::$app->session->setFlash("success","添加分类成功");
                    //跳转
                    return $this->redirect(['index']);
                }

            }else{
                //TODO
              //  var_dump($model->errors);exit;
              //  var_dump($model->firstErrors);
               // $model->addError('name')
            }





        }

        //视图
        return $this->render('add',compact('model'));



    }

    public function actionEdit($id){
        //创建一个数据模型对象
        $model=ArticleCategory::findOne($id);

        //判断Post
        $request=\Yii::$app->request;
        if ($request->isPost){
            //数据绑定
            $model->load($request->post());

            //后台验证
            if ($model->validate()) {

                //保存数据
                if ($model->save()) {
                    //提示
                    \Yii::$app->session->setFlash("success","添加分类成功");
                    //跳转
                    return $this->redirect(['index']);
                }

            }else{
                //TODO
                //  var_dump($model->errors);exit;
                //  var_dump($model->firstErrors);
                // $model->addError('name')
            }





        }

        //视图
        return $this->render('add',compact('model'));



    }


}
