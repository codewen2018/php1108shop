<?php

namespace backend\controllers;

use backend\models\Category;
use yii\db\Exception;
use yii\helpers\Json;

class CategoryController extends \yii\web\Controller
{
    public function actionIndex()
    {
        //得到所有数据
        $cates = Category::find()->orderBy('tree,lft')->all();
        return $this->render('index', compact('cates'));
    }

    public function actionAdd()
    {
        $cate = new Category();

        //查出所有分类
        $cates = Category::find()->asArray()->all();
        //追加一个一级分类
        $cates[] = ['id' => 0, 'name' => '一级分类', 'parent_id' => 0];
        //转JSON字符串
        $catesJson = Json::encode($cates);

        //var_dump($catesJson);exit;

        //Post
        $request = \Yii::$app->request;
        if ($request->isPost) {
            //数据绑定
            $cate->load($request->post());

            //后台验证
            if ($cate->validate()) {
                //如果parent_id=0 添加一级分类
                if ($cate->parent_id == 0) {
                    //创建一个一级分类
                    $cate->makeRoot();
                    //
                    \Yii::$app->session->setFlash("success", "创建一级分类:" . $cate->name . "成功");
                    //刷新
                    return $this->refresh();

                } else {
                    //添加子类
                    //1 找到父分类对象
                    $cateParent = Category::findOne($cate->parent_id);

                    //2 创建一个新的分类
                    /*    $cate=new Category();
                        $cate->name="电视";
                        $cate->parent_id=1;*/

                    //3 把新的分类加入父分类中

                    $cate->prependTo($cateParent);
                    \Yii::$app->session->setFlash("success", "创建{$cateParent->name}分类的子分类:" . $cate->name . " 成功");
                    //刷新
                    return $this->refresh();

                }


            } else {

                var_dump($cate->errors);
                exit;

            }


        }
        return $this->render('add', compact('cate', 'catesJson'));


    }

    public function actionEdit($id)
    {
        $cate = Category::findOne($id);

        //查出所有分类
        $cates = Category::find()->asArray()->all();
        //追加一个一级分类
        $cates[] = ['id' => 0, 'name' => '一级分类', 'parent_id' => 0];
        //转JSON字符串
        $catesJson = Json::encode($cates);

        //var_dump($catesJson);exit;

        //Post
        $request = \Yii::$app->request;
        if ($request->isPost) {
            //数据绑定
            $cate->load($request->post());

            //后台验证
            if ($cate->validate()) {


                try {

                    //如果parent_id=0 添加一级分类
                    if ($cate->parent_id == 0) {
                        //创建一个一级分类
                        $cate->save();
                        //
                        \Yii::$app->session->setFlash("success", "修改一级分类:" . $cate->name . "成功");


                    } else {
                        //添加子类
                        //1 找到父分类对象
                        $cateParent = Category::findOne($cate->parent_id);

                        //2 创建一个新的分类
                        /*    $cate=new Category();
                            $cate->name="电视";
                            $cate->parent_id=1;*/

                        //3 把新的分类加入父分类中

                        $cate->prependTo($cateParent);
                        \Yii::$app->session->setFlash("success", "修改成功");


                    }
//刷新
                    return $this->redirect(['index']);
                } catch (Exception $exception) {

                    \Yii::$app->session->setFlash("danger",$exception->getMessage());
                }



            } else {

                var_dump($cate->errors);
                exit;

            }


        }
        return $this->render('add', compact('cate', 'catesJson'));


    }

    public function actionTest()
    {
        /* //创建一个一级分类
         $cate=new Category();
         $cate->name="电脑";
         //创建一个一级分类
         $cate->makeRoot();*/
        //添加一个子分类
        //1 找到父分类对象
        $cateParent = Category::findOne(1);

        //2 创建一个新的分类
        $cate = new Category();
        $cate->name = "电视";
        $cate->parent_id = 1;

        //3 把新的分类加入父分类中

        $cate->prependTo($cateParent);

        //var_dump($cate->errors);


    }

    /**
     * 商品分类删除
     */
    public function actionDel($id){

        if (Category::findOne($id)->deleteWithChildren()) {
            return $this->redirect(['index']);
        }



    }

}
