<?php

namespace backend\controllers;

use backend\models\Article;
use backend\models\ArticleCategory;
use backend\models\ArticleContent;
use yii\helpers\ArrayHelper;

class ArticleController extends \yii\web\Controller
{

    public function actions()
    {
        return [
            'upload' => [
                'class' => 'kucha\ueditor\UEditorAction',
            ]
        ];
    }

    public function actionIndex()
    {
        //得到所有数据
        $articles=Article::find()->all();
        //视图
        return $this->render('index',compact('articles'));
    }
    public function actionAdd(){
        //创建文章模型对象
     $model=new Article();

     //创建文章内容模型对象
        $content=new ArticleContent();

     //取到分类数据
        $cates=ArticleCategory::find()->all();
        //把二维数组转一维
        $catesArr=ArrayHelper::map($cates,'id','name');

        //判断POST提交
        $request=\Yii::$app->request;
        if ($request->isPost){

            //数据绑定
            $model->load($request->post());

            //后台验证
            if ($model->validate()){
                //保存数据
                if ($model->save()) {
                    //再保存文章内容
                    //文章内容数据绑定
                    $content->load($request->post());
                    //文章内容后台验证
                    if ($content->validate()){
                        //给文章Id赋值
                        $content->article_id=$model->id;
                        if ($content->save()) {

                        //保存文章内容
                            //提示
                            \Yii::$app->session->setFlash('success','添加成功');
                            //跳转
                            return $this->redirect(['index']);


                        }

                    }
                }

            }else{

                //TODO
                var_dump($model->errors);exit;

            }





        }

        //var_dump($catesArr);exit;

     return $this->render('add',compact('model','content','catesArr'));


    }

}
