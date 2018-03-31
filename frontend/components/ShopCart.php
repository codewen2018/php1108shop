<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/31
 * Time: 9:05
 */

namespace frontend\components;


use frontend\models\Cart;
use yii\base\Component;
use yii\web\Cookie;

class ShopCart extends Component
{
    //声明一个私有属性用来存购物车数据
    private $cart;

    //创建对象的时候自动调用执行
   /* public function __construct(array $config = [])
    {
        //得到COokie对象
        $getCookie = \Yii::$app->request->cookies;
        //得到原来购物车数据 赋值给$cart属性
        $this->cart = $getCookie->getValue('cart', []);
        parent::__construct($config);
    }*/
   //自动调用执行的
   public function init()
   {
       //得到COokie对象
    $getCookie = \Yii::$app->request->cookies;
       //得到原来购物车数据 赋值给$cart属性
     $this->cart = $getCookie->getValue('cart', []);
       parent::init();
   }

    //增
    public function add($id,$num){
        //判断当前添加的商品ID在购物车中是否已经存在 如果存在，执行+ 否则 新增

        //var_dump(array_key_exists($id,$cart));exit;
        if (array_key_exists($id,$this->cart)) {
            //已经存在 值+$amount
            $this->cart[$id] += $num;
        } else {
            //新增
            $this->cart[$id] = (int)$num;
        }

        return $this;
    }
    //删
    public function del($id){
       //删除当前数据
       unset($this->cart[$id]);

       return $this;
    }
    //清空cookie中的数据
    public function flush(){

       //清空本地购物车
       $this->cart=[];
       return $this;

    }
    //改
    public function update($id,$num){
       //修改对应的数据
        if ($this->cart[$id]){
            $this->cart[$id] = $num;
        }
        return $this;
    }
    //查
    public function get(){
       return $this->cart;
    }

    //数据库同步操作
    public function dbSyn(){

        //1. 取出cookie中的数据  [1=>2,5=>1]


        //  var_dump($cart);exit;
        //2.把数据同步到数据库中
        //当前用户
        $userId=\Yii::$app->user->id;

        foreach ($this->cart as $goodId=>$num){

            //判断当前用户当前商品有没有存在

            $cartDb=Cart::findOne(['goods_id'=>$goodId,'user_id'=>$userId]);
            //判断
            if ($cartDb){
                //+ 修改操作
                $cartDb->num+=$num;
                // $cart->save();


            }else{
                //创建对象
                $cartDb=new Cart();
                //赋值
                $cartDb->goods_id=$goodId;
                $cartDb->num=$num;
                $cartDb->user_id=$userId;
            }
            //保存
            $cartDb->save();

        }

           return $this;

    }

    //保存
    public function save(){
        //1.创建设置COokie对象
        $setCookie = \Yii::$app->response->cookies;

        //2.创建一个COokie对象
        $cookie = new Cookie([
            'name' => 'cart',
            'value' => $this->cart,
            'expire' => time()+3600*24*30*12
        ]);
        //2.通过设置COokie对象来添加一个COokie
        $setCookie->add($cookie);
    }



}