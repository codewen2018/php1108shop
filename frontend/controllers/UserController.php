<?php

namespace frontend\controllers;

use frontend\components\ShopCart;
use frontend\models\Cart;
use frontend\models\User;
use Mrgoon\AliSms\AliSms;
use yii\helpers\Json;

class UserController extends \yii\web\Controller
{

    public $enableCsrfValidation=false;
    public function actions()
    {
        return [
            'code' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
                'minLength' => 3,
                'maxLength' => 3,
                'foreColor' => 0x55FF00
            ],
        ];
    }
  //  public $layout=false;
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * 用户注册
     */
    public function actionReg(){

        //判断是不是POST提交

        $request=\Yii::$app->request;
        if ($request->isPost){

          //  exit('1111');
            //
           // var_dump($request->post());exit;
//创建模型对象
            $user=new User();
            //数据绑定
            $user->load($request->post());

            //后台验证
            if ($user->validate()) {

                $user->auth_key=\Yii::$app->security->generateRandomString();//令牌
                $user->password_hash=\Yii::$app->security->generatePasswordHash($user->password);//密码
                //新增用户
                if ($user->save(false)) {
                    //跳转到登录页面
                    $result=[
                        'status'=>1,
                        'msg'=>'注册成功',
                        'data'=>"",
                    ];
                    return Json::encode($result);
                }


            }else{

                $result=[
                    'status'=>0,
                    'msg'=>'注册失败',
                    'data'=>$user->errors,
                ];
                return Json::encode($result);
                //var_dump($user->errors);exit;
            }

/*
            echo "<pre>";
            var_dump($user);

            $user->load($request->post());
            var_dump($user);exit;

            $user->username=$request->post('username');
            $user->password_hash=\Yii::$app->security->generatePasswordHash($request->post('password'));
            $user->email=$request->post('email');
            $user->mobile=$request->post('tel');*/

          //  $user->save();




        }

        //显示视图

        return $this->render('reg');



    }

    /**
     * 用户登录
     */
    public function actionLogin()
    {

        //Post提交
        $request=\Yii::$app->request;
        if ($request->isPost){
            //创建一个新的模型对象
            $model=new User();

            //设置场景
            $model->setScenario(User::SCENARIO_LOGIN);

            //绑定数据

            $model->load($request->post());

            //后台验证
            if ($model->validate()){
                //找出用户
                $user=User::findOne(['username'=>$model->username]);
                if ($user && \Yii::$app->security->validatePassword($model->password,$user->password_hash)){
                          //登录成功
                    \Yii::$app->user->login($user,$model->rememberMe?3600*24*7:0);

                    //同步到数据库
                    (new ShopCart())->dbSyn()->flush()->save();
                  /*  //同步本地cookie中购物车数据到数据库中去

                    //1. 取出cookie中的数据  [1=>2,5=>1]
                    $cart=(new ShopCart())->get();

                  //  var_dump($cart);exit;
                    //2.把数据同步到数据库中
                    //当前用户
                    $userId=\Yii::$app->user->id;

                    foreach ($cart as $goodId=>$num){

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

                    }*/

                    //3.清空本地cookie中的数据


                    $result= [
                        'status'=>1,
                        'msg'=>'登录成功',
                        'data'=>null
                    ];
                    return Json::encode($result);

                }else{
                    //用户名或密码错误
                   // $model->addError("username","用户名不存在")
                    //验证失败
                    $result= [
                        'status'=>0,
                        'msg'=>'用户名或密码错误',
                        'data'=>$model->errors
                    ];
                    return Json::encode($result);
                }

            }else{

                //验证失败
               $result= [
                    'status'=>0,
                    'msg'=>'输入有误',
                    'data'=>$model->errors
                ];
               return Json::encode($result);
            }




        }
        return $this->render('login');
    }

    public function actionSendSms($mobile)
    {
        //1.一个手机号一分钟之内只能发一次  每日最多发5次 数据库
        /*
         *   id    tel       day    count    send_time      code
         *   1     138    20180404    1          10:20      1111
         *
         * 如果 当前手机号当天没有数据就执行新增 count=1  否则 执行修改 只需给count+1
         *
         * time()-send_time()<60 返回 提示还没有一分钟，不能发送
         *
         *
         * 1号验证1111 2号1111    1111 1111  5分钟之内
         */




        //1. 生成验证码 13899998888=>111111
        $code=rand(100000,999999);
        //2. 把这个验证码发送给$mobile
        $config = [
            'access_key' => 'LTAISfYC85mDFNHs',
            'access_secret' => 'Q5AR3tegQ5AAo3VMI9wPgxdz3bmqPd',
            'sign_name' => '冬天',//签名
        ];

       // $aliSms = new Mrgoon\AliSms\AliSms();
        $aliSms=new AliSms();//创建一个短信发送的对象专用用来发送短信
        $response = $aliSms->sendSms($mobile, 'SMS_128651035', ['code'=> $code], $config);

        if ($response->Message=="OK"){

            //3. 把code保存到Session中  把手机号当键名 验证码当值
            $session=\Yii::$app->session;

            //$session->set("tel_18584563931","163940");
            $session->set("tel_".$mobile,$code);

            //4.测试
          //  return $code;
        }else{
            var_dump($response->Message);
        }



    }

    public function actionCheckSms($mobile,$code){
        //1。通过手机号取出之前发送出去的code
        $codeOld=\Yii::$app->session->get("tel_".$mobile);

        //2.判断输入8code是否正确
        if ($code==$codeOld){

            echo "OK";
        }else{
            echo "fuck";
        }




    }

    public function actionLogout(){

        if (\Yii::$app->user->logout()) {
            return $this->redirect(['login']);
        }

    }

}
