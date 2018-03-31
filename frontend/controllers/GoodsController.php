<?php

namespace frontend\controllers;

use backend\models\Goods;
use frontend\components\ShopCart;
use frontend\models\Cart;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\web\Cookie;

class GoodsController extends \yii\web\Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * 商品详情
     * @param $id 商品Id
     */
    public function actionDetail($id)
    {

        //找到当前商品
        $good = Goods::findOne($id);

        //找到当前商品对应的所有图片

        //var_dump($good->images);exit;

        return $this->render('detail', compact('good'));
    }

    /**
     * 添加购物车
     * @param $id 商品Id
     * @param $num 商品数量
     */
    public function actionAddCart($id, $amount)
    {

        if (\Yii::$app->user->isGuest) {

          /*  $cart=new ShopCart();
            $cart->add($id,$amount)->save();*/
            (new ShopCart())->add($id,$amount)->save();

           // $cart->save();
          /*  //未登录存COokie
            //得到COokie对象
            $getCookie = \Yii::$app->request->cookies;
            //得到原来购物车数据
            $cart = $getCookie->getValue('cart', []);

            //判断当前添加的商品ID在购物车中是否已经存在 如果存在，执行+ 否则 新增

            //var_dump(array_key_exists($id,$cart));exit;
            if (array_key_exists($id, $cart)) {
                //已经存在 值+$amount
                $cart[$id] += $amount;
            } else {
                //新增
                $cart[$id] = (int)$amount;

            }

            // var_dump($cart);exit;
            // 把$Id当键 把$amount当值  [3=>4,2=>5]
            //1.创建设置COokie对象
            $setCookie = \Yii::$app->response->cookies;

            //2.创建一个COokie对象
            $cookie = new Cookie([
                'name' => 'cart',
                'value' => $cart,
                'expire' => time()+3600*24*30*12
            ]);
            //2.通过设置COokie对象来添加一个COokie
            $setCookie->add($cookie);*/




        } else {
            //已登录 存数据库

          //当前用户
            $userId=\Yii::$app->user->id;
            //判断当前用户当前商品有没有存在
            $cart=Cart::findOne(['goods_id'=>$id,'user_id'=>$userId]);
            //判断
            if ($cart){
                //+ 修改操作
                $cart->num+=$amount;
               // $cart->save();


            }else{
                //创建对象
                $cart=new Cart();
                //赋值
                $cart->goods_id=$id;
                $cart->num=$amount;
                $cart->user_id=$userId;
            }
            //保存
            $cart->save();


        }

        return $this->redirect(['cart-list']);

        //var_dump($id,$amount);
    }

    public function actionCartList()
    {

        //判断登录
        if (\Yii::$app->user->isGuest) {
            //从cookie中取出购物车数据
            $cart = \Yii::$app->request->cookies->getValue('cart', []);

            //取出$cart中的所有key值
            //  var_dump(array_keys($cart));exit;
            $goodIds = array_keys($cart);

            //取购物车的所有商品
            $goods = Goods::find()->where(['in', 'id', $goodIds])->all();


            //  var_dump($goods);exit;


        } else {
            //已登录 数据库

            //从cookie中取出购物车数据
          //  $cart = \Yii::$app->request->cookies->getValue('cart', []);
            $cart=Cart::find()->where(['user_id'=>\Yii::$app->user->id])->all();

            //把二维数组提取成一维数组 【‘商品Id’=》商品数量,...】
            $cart=ArrayHelper::map($cart,'goods_id','num');

          //  var_dump($cart);exit;

            //取出$cart中的所有key值
            //  var_dump(array_keys($cart));exit;
            $goodIds = array_keys($cart);

            //取购物车的所有商品
            $goods = Goods::find()->where(['in', 'id', $goodIds])->all();


            //  var_dump($goods);exit;


        }
        return $this->render('list', compact('goods', 'cart'));
    }

    public function actionUpdateCart($id, $amount)
    {

        if (\Yii::$app->user->isGuest) {

            (new ShopCart())->update($id,$amount)->save();
           /* //1`.从COokie取出购物车数据
            $cart = \Yii::$app->request->cookies->getValue('cart', []); //[1=>6,5=>5]
            //2 修改对应的数据
            $cart[$id] = $amount;
            //3 把$cart存到购物车中
            //3.1.创建设置COokie对象
            $setCookie = \Yii::$app->response->cookies;

            //3.2.创建一个COokie对象
            $cookie = new Cookie([
                'name' => 'cart',
                'value' => $cart
            ]);
            //2.通过设置COokie对象来添加一个COokie
            $setCookie->add($cookie);*/
        }


    }

    /**
     * 删除购物车
     * @param $id 商品Id
     */
    public function actionDelCart($id)
    {
        if (\Yii::$app->user->isGuest) {
            (new ShopCart())->del($id)->save();
           /* //1`.从COokie取出购物车数据
            $cart = \Yii::$app->request->cookies->getValue('cart', []); //[1=>6,5=>5]
            //2 删除对应的数据
            unset($cart[$id]);
            //3 把$cart存到购物车中
            //3.1.创建设置COokie对象
            $setCookie = \Yii::$app->response->cookies;

            //3.2.创建一个COokie对象
            $cookie = new Cookie([
                'name' => 'cart',
                'value' => $cart
            ]);
            //2.通过设置COokie对象来添加一个COokie
            $setCookie->add($cookie);*/

            return Json::encode([
                'status'=>1,
                'msg'=>'删除成功'
            ]);
        }
    }

    public function actionTest()
    {

        $getCookie = \Yii::$app->request->cookies;
        var_dump($getCookie->getValue('cart'));
    }
}
