<?php

namespace backend\controllers;

use backend\models\Brand;
use yii\web\UploadedFile;

class BrandController extends \yii\web\Controller
{
    /**
     * 品牌列表
     * @return string
     */
    public function actionIndex()
    {
        //找到所有数据
        $brands=Brand::find()->all();

        return $this->render('index',compact('brands'));
    }

    /**
     * 品牌添加
     * @return string|\yii\web\Response
     */
    public function actionAdd(){

        $model=new Brand();

        //判断POST
        if (\Yii::$app->request->isPost){
            //数据绑定
            $model->load(\Yii::$app->request->post());
            //上传图片
            $model->img=UploadedFile::getInstance($model,'img');

            //定义一个图片路径
            $imgPath="";
            //如果上传了图片，移动图片
            if ($model->img!==null){
             //拼路径
                $imgPath="images/".time().".".$model->img->extension;
                //移动 有坑
                $model->img->saveAs($imgPath,false);

            }

            //后台验证
            if ($model->validate()) {
                //把图片路径赋值给logo
                $model->logo=$imgPath;
                //保存数据
                if ($model->save()) {
                    //提法
                    \Yii::$app->session->setFlash('success','添加成功');
                    //跳转
                    return $this->redirect(['index']);


                }

            }else{

                //TODO:
                var_dump($model->errors);exit;


            }








        }

        return $this->render('add',compact('model'));



    }

    /**
     * 品牌编辑
     * @param $id 品牌Id
     * @return string|\yii\web\Response
     */
    public function actionEdit($id){

        //找出需要编辑
        $model=Brand::findOne($id);

        //判断POST
        if (\Yii::$app->request->isPost){
            //数据绑定
            $model->load(\Yii::$app->request->post());
            //上传图片
            $model->img=UploadedFile::getInstance($model,'img');

            //定义一个图片路径
            $imgPath="";
            //如果上传了图片，移动图片
            if ($model->img!==null){
                //拼路径
                $imgPath="images/".time().".".$model->img->extension;
                //移动 有坑
                $model->img->saveAs($imgPath,false);

            }

            //后台验证
            if ($model->validate()) {

                //判断图片是否为空
                if ($imgPath){
                    //TODO 删除之前的图片 unlink()

                    //把图片路径赋值给logo
                    $model->logo=$imgPath;


                }
             // $model->logo=$imgPath?:$model->logo;

                //保存数据
                if ($model->save()) {
                    //提法
                    \Yii::$app->session->setFlash('success','添加成功');
                    //跳转
                    return $this->redirect(['index']);


                }

            }else{

                //TODO:
                var_dump($model->errors);exit;


            }








        }

        return $this->render('add',compact('model'));



    }

    /**
     * 品牌删除
     * @param $id 品牌Id
     */
    public function actionDel($id){

        if (Brand::findOne($id)->delete()) {
            //提示
            \Yii::$app->session->setFlash('success','删除成功');

            return $this->redirect(['index']);
        }
    }
}
