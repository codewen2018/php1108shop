<?php

namespace frontend\controllers;

use frontend\models\User;
use Mrgoon\AliSms\AliSms;
use yii\helpers\Json;

class UserController extends \yii\web\Controller
{

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

    public function actionSendSms($mobile)
    {
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

            //$session->set("tel_13899998888","111111");
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

}
