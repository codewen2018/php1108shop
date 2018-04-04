<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/4
 * Time: 9:44
 */

namespace console\controllers;


use backend\models\Goods;
use backend\models\Order;
use backend\models\OrderDetail;
use yii\console\Controller;

class OrderController extends Controller
{
    public function actionClear()
    {

        while (true){
            //1 找出超时未支付 time()-create_time>15*60  status=1
            //time()-create_time>15*60 ===>>>  time()-15*60>create_time
            //   create_time<time()-900

            $orders=Order::find()->where(['status'=>1])->andWhere(['<','create_time',time()-900])->asArray()->all();

            //1.1拿到所有$orders的ID

            $orderIds=array_column($orders,'id');


            //2 给所有符合条件订单的status=0 已取消

            Order::updateAll(['status'=>0],['in','id',$orderIds]);

            //3 得把订单对应的商品库存还原
            //循环订单
            foreach ($orders as $order){
                //每个订单对应的商品详情

                $orderDetails=OrderDetail::find()->where(['order_id'=>$order['id']])->all();
                //循环商品详情
                foreach ($orderDetails as $orderDetail){


                    //还原库存
                    /*  $good=Goods::findOne($orderDetail->goods_id);
                      $good->stock+=$orderDetail->amount;
                      $good->save();*/
                    Goods::updateAllCounters(['stock'=>$orderDetail->amount],['id'=>$orderDetail->goods_id]);

                }





            }

            if ($orderIds){
                echo "completed ".implode(",",$orderIds).PHP_EOL;
            }


        sleep(1);
        }



    }

}