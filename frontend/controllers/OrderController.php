<?php

namespace frontend\controllers;

use backend\models\Delivery;
use backend\models\Goods;
use backend\models\Order;
use backend\models\OrderDetail;
use backend\models\PayType;
use frontend\models\Address;
use frontend\models\Cart;
use yii\db\Exception;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;

class OrderController extends \yii\web\Controller
{
    public function actionIndex()
    {
        //判断有没有登录
        if (\Yii::$app->user->isGuest){
            return $this->redirect(['user/login','url'=>'/order/index']);
        }
        //用户Id
        $userId=\Yii::$app->user->id;


        //收货人地址
        $addresss=Address::find()->where(['user_id'=>$userId])->all();

        //配送方式
        $deliverys=Delivery::find()->all();
        //支付方式
        $payTypes=PayType::find()->all();

        //取出商品
        $cart=Cart::find()->where(['user_id'=>\Yii::$app->user->id])->asArray()->all();


        //把二维数组提取成一维数组 【‘商品Id’=》商品数量,...】
        $cart=ArrayHelper::map($cart,'goods_id','num');//[5=>3,1=>1]
        //var_dump($cart);exit;
        //取出$cart中的所有key值
        $goodIds = array_keys($cart);
        //取购物车的所有商品
        $goods = Goods::find()->where(['in', 'id', $goodIds])->all();

        //商品总价
        $shopPrice=0;
        //商品总数
        $shopNum=0;
        foreach ($goods as $good){

            //算商品总价
            $shopPrice+=$good->shop_price*$cart[$good->id];
            $shopNum+=$cart[$good->id];
        }
        //二位小数
        $shopPrice=number_format($shopPrice,2);


        $request=\Yii::$app->request;
        //判断POST提交
        if ($request->isPost){


            $db = \Yii::$app->db;
            $transaction = $db->beginTransaction();//开启事务

            try {

                //创建订单对象
                $order=new Order();
                /* var_dump($request->post('address_id'));
                 exit;*/
                //取出地址
                $addressId=$request->post('address_id');
                $address=Address::findOne(['id'=>$addressId,'user_id'=>$userId]);

                //取出配送方式
                $deliveryId=$request->post('delivery');
                $delivery=Delivery::findOne($deliveryId);

                //取出配送方式
                $payTypeId=$request->post('pay');
                $payType=PayType::findOne($payTypeId);

                //给order赋值
                $order->user_id=$userId;//用户ID
                //收集信息
                $order->name=$address->name;
                $order->province=$address->province;
                $order->city=$address->city;
                $order->area=$address->county;
                $order->detail_address=$address->address;
                $order->tel=$address->mobile;

                $order->delivery_id=$deliveryId;//快递ID
                $order->delivery_name=$delivery->name;//配送方式
                $order->delivery_price=$delivery->price;//运费

                $order->payment_id=$payTypeId;//支付方式Id
                $order->payment_name=$payType->name;//支付名称


                //订单总价
                $order->price=$shopPrice+$delivery->price;

                //订单状态
                $order->status=1;//0 已取消 1 待支付 2 等待发货 3等待确认

                //订单号
                $order->trade_no=date("ymdHis").rand(1000,9999);

                $order->create_time=time();


                //var_dump($goods);exit;
                //保存数据
                if ($order->save()) {

                    //循环商品 入商品详情表
                    foreach ($goods as $good){
                        //判断当前商品库存够不够
                        //1 .找出当前商品
                        $curGood=Goods::findOne($good->id);
                        //2.判断库存
                        if ($cart[$good->id]>$curGood->stock){
                           // exit("库存不足");
                            //抛出异常
                            throw new Exception("库存不足");
                        }

                        $orderDetail=new OrderDetail();
                        $orderDetail->order_id=$order->id;
                        $orderDetail->goods_id=$good->id;
                        $orderDetail->amount=$cart[$good->id];
                        $orderDetail->goods_name=$good->name;
                        $orderDetail->logo=$good->logo;
                        $orderDetail->price=$good->shop_price;
                        $orderDetail->total_price=$good->shop_price*$orderDetail->amount;
                        //保存数据
                        if ($orderDetail->save()) {

                            // exit($cart[$good->id]);
                            //把当前商品的库存减掉
                            $curGood->stock=$curGood->stock-$cart[$good->id];

                            //  echo $curGood->stock;
                            //exit();
                            $curGood->save(false);
                            //  var_dump($curGood->errors);
                            //  exit();
                        }
                    }




                }



                //清空购物车
                Cart::deleteAll(['user_id'=>$userId]);


                $transaction->commit();//提交事务

                return Json::encode([
                    'status'=>1,
                    'msg'=>'订单提交成功'
                ]);

            } catch(Exception $e) {

                $transaction->rollBack();//事务回滚

               return Json::encode([
                   'status'=>0,
                   'msg'=>$e->getMessage()
               ]);
            }






        }

        return $this->render('index',compact('addresss','deliverys','payTypes','cart','goods','shopPrice','shopNum'));
    }

    public function actionAdd(){

        //必需要用事务

        //1.新增订单

        //2.循环商品再新增商品详情

        //3. 减商品库存





    }

}
