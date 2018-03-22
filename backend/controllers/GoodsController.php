<?php

namespace backend\controllers;

use backend\models\Brand;
use backend\models\Category;
use backend\models\Goods;
use backend\models\GoodsGallery;
use backend\models\GoodsIntro;
use yii\data\Pagination;
use yii\helpers\ArrayHelper;

class GoodsController extends \yii\web\Controller
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
        //得到所有数据 分页
        // 创建一个 DB 查询来获得所有 status 为 1 的文章
        $query = Goods::find();
        $minPrice=\Yii::$app->request->get('minPrice');
        $maxPrice=\Yii::$app->request->get('maxPrice');
        $keyword=\Yii::$app->request->get('keyword');
        $status=\Yii::$app->request->get('status');

        //加条件

        //最小值
        if ($minPrice){
            $query->andWhere("shop_price>={$minPrice}");
        }
        //最大值
        if ($maxPrice){
            $query->andWhere(['<=','shop_price',$maxPrice]);
        }
        //商品名称和货号
        if ($keyword!==""){

            $query->andWhere("name like '%{$keyword}%' or sn like '%{$keyword}%'");

        }
       //判断status 字符串  所有的Http请求的参数都字符串，判断必需用3等加字符
        if ($status==="0" || $status==="1"){


            $query->andWhere(['status'=>$status]);
        }



// 得到文章的总数（但是还没有从数据库取数据）
        $count = $query->count();

// 使用总数来创建一个分页对象
        $pagination = new Pagination(
            [
                'totalCount' => $count,//总数
                'pageSize' => 3
            ]
        );

// 使用分页对象来填充 limit 子句并取得文章数据
        $goods = $query->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();

        return $this->render('index', compact('pagination','goods'));
    }

    /**
     * 商品添加
     */
    public function actionAdd()
    {
        //创建商品模型对象
        $good = new Goods();
        //创建商品详情模型对象
        $intro = new GoodsIntro();
        //得到所有分类
        $cates = Category::find()->orderBy('tree,lft')->all();
        //得到id 到 nameText的值
        $cates = ArrayHelper::map($cates, 'id', 'nameText');
        //var_dump($cates);exit;

        //得到所有品牌
        $brands = Brand::find()->all();
        //得到id 到 nameText的值
        $brands = ArrayHelper::map($brands, 'id', 'name');
        //var_dump($cates);exit;


        //判断Post提交
        $request = \Yii::$app->request;
        if ($request->isPost) {

            //绑定Goods的数据
            $good->load($request->post());

            //绑定商品详情的数据
            $intro->load($request->post());

            //后台验证
            if ($good->validate() && $intro->validate()) {

                // var_dump($good->images);exit;

                //判断sn 有没有值

                //var_dump($good->sn);exit;
                if (!$good->sn) {
                    //自动生成  年月日 当日商品数量+1  20180320 00001

                    $dayTime = strtotime(date('Ymd'));//当天0时0分0秒的时间戳
                    //1 找出当日商品数量
                    $count = Goods::find()->where(['>', 'create_at', $dayTime])->count();
                    //加1
                    $count += 1;
                    $countStr = "0000" . $count;//1===>00001 99999===>000099999
                    //取后面5位
                    $countStr = substr($countStr, -5);

                    $good->sn = date('Ymd') . $countStr;
                    //var_dump($good->sn);exit;

                }


                //保存数据
                if ($good->save()) {
                    //商品内容
                    $intro->goods_id = $good->id;
                    $intro->save();


                    //多图操作
                    //循环images
                    foreach ($good->images as $image) {

                        //重点强调 一定要新建对象
                        $gallery = new GoodsGallery();
                        //赋值
                        $gallery->goods_id = $good->id;
                        $gallery->path = $image;
                        //保存图片
                        $gallery->save();


                    }

                    //提示
                    \Yii::$app->session->setFlash('success', '商品添加成功');
                    return $this->redirect(['index']);
                }


            } else {

                //TODO
                var_dump($good->errors);
                exit();
            }


        }


        //视图
        return $this->render('add', compact('good', 'cates', 'brands', 'intro'));
    }

    public function actionEdit($id)
    {
        //创建商品模型对象
        $good = Goods::findOne($id);
        //创建商品详情模型对象
        $intro = GoodsIntro::findOne(['goods_id' => $id]);
        //得到所有分类
        $cates = Category::find()->orderBy('tree,lft')->all();
        //得到id 到 nameText的值
        $cates = ArrayHelper::map($cates, 'id', 'nameText');
        //var_dump($cates);exit;

        //得到所有品牌
        $brands = Brand::find()->all();
        //得到id 到 nameText的值
        $brands = ArrayHelper::map($brands, 'id', 'name');
        //var_dump($cates);exit;


        //判断Post提交
        $request = \Yii::$app->request;
        if ($request->isPost) {

            //绑定Goods的数据
            $good->load($request->post());

            //绑定商品详情的数据
            $intro->load($request->post());

            //后台验证
            if ($good->validate() && $intro->validate()) {

                // var_dump($good->images);exit;

                //判断sn 有没有值

                //var_dump($good->sn);exit;
                if (!$good->sn) {
                    //自动生成  年月日 当日商品数量+1  20180320 00001

                    $dayTime = strtotime(date('Ymd'));//当天0时0分0秒的时间戳
                    //1 找出当日商品数量
                    $count = Goods::find()->where(['>', 'create_at', $dayTime])->count();
                    //加1
                    $count += 1;
                    $countStr = "0000" . $count;//1===>00001 99999===>000099999
                    //取后面5位
                    $countStr = substr($countStr, -5);

                    $good->sn = date('Ymd') . $countStr;
                    //var_dump($good->sn);exit;

                }


                //保存数据
                if ($good->save()) {
                    //商品内容
                   // $intro->goods_id = $good->id;
                    $intro->save();


                    //多图操作
                    //操作之前一定要先删除当前商品所对应的所有图片
                    GoodsGallery::deleteAll(['goods_id'=>$id]);
                    //循环images
                    foreach ($good->images as $image) {

                        //重点强调 一定要新建对象
                        $gallery = new GoodsGallery();
                        //赋值
                        $gallery->goods_id = $good->id;
                        $gallery->path = $image;
                        //保存图片
                        $gallery->save();


                    }

                    //提示
                    \Yii::$app->session->setFlash('success', '商品添加成功');
                    return $this->redirect(['index']);
                }


            } else {

                //TODO
                var_dump($good->errors);
                exit();
            }


        }
//从数据库中找出当前商品对应的所有图片
        $images=GoodsGallery::find()->where(['goods_id'=>$id])->asArray()->all();



        //把二维数组转成指定字段的一维数组
        $images=array_column($images,'path');
        //var_dump($images);exit;
        //给images赋值
        $good->images = $images;
        //视图
        return $this->render('add', compact('good', 'cates', 'brands', 'intro'));
    }

    public function actionDel($id){

        //删除商品表
        //删除内容表
        //删除图片

    }

}
