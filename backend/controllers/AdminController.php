<?php

namespace backend\controllers;

use backend\models\Admin;
use backend\models\LoginForm;

class AdminController extends \yii\web\Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionLogin(){

        //表单模型对象
        $model=new LoginForm();

        //Post提交
        $request=\Yii::$app->request;
        if ($request->isPost){
            //绑定数据
            $model->load($request->post());

            //后台验证
            if ($model->validate()){
              //  var_dump($model->rememberMe);exit;
                //1.通过用户名找出用户对象
                $admin=Admin::findOne(['username'=>$model->username,'status'=>1]);

                //2.判断用户是否存在
                if ($admin){
                    //3.验证密码
                    if (\Yii::$app->security->validatePassword($model->password,$admin->password_hash)){
                        //4.密码验证成功 登录
                        \Yii::$app->user->login($admin,$model->rememberMe?3600*24*7:0);

                        //5. 设置登录时间 和Ip
                        $admin->login_at=time();
                        //用户Ip
                        $admin->login_ip=ip2long(\Yii::$app->request->userIP);
                        //更新用户
                        if ($admin->save()) {
                            \Yii::$app->session->setFlash('success','登录成功');
                            return $this->redirect(['index']);
                        }



                    }else{

                        //5.密码错误
                        $model->addError('password','密码错误');
                    }


                }else{
                    //用户不存在或者已禁用
                    $model->addError('username','用户不存在或者已禁用');

                }




            }else{
                //打印错误

            }





        }

        //视图

        return $this->render('login',compact('model'));



    }

    public function actionAdd(){

        //
        $admin=new Admin();

        //直接绑定数据并判断POST提交
        if ($admin->load(\Yii::$app->request->post()) && $admin->validate()){

            //给密码加密
            $admin->password_hash=\Yii::$app->security->generatePasswordHash($admin->password_hash);
            //设置令牌 随机字符串 32位
            $admin->auth_key=\Yii::$app->security->generateRandomString();
            if ($admin->save()) {
                //提示
                \Yii::$app->session->setFlash('success','添加成功');
                return $this->redirect(['index']);
            }



        }

        return $this->render('add',compact('admin'));


    }

    public function actionLogout(){

        if (\Yii::$app->user->logout()) {
            return $this->redirect(['login']);
        }


    }

    public function actionEdit($id){

        //
        $admin=Admin::findOne($id);
        //原来的HASH
        $password=$admin->password_hash;

        //设置场景
        $admin->setScenario('edit');

        //直接绑定数据并判断POST提交
        if ($admin->load(\Yii::$app->request->post()) && $admin->validate()){

            //判断用户有没有输入密码
            //var_dump((bool)$admin->password_hash);exit;

           /* if ($admin->password_hash){

               // exit('1');
                //给密码加密
                $admin->password_hash=\Yii::$app->security->generatePasswordHash($admin->password_hash);

            }else{
                $admin->password_hash=$password;
            }*/
           //如果用户有输入就用用户新的密码否则不修改密码
           $admin->password_hash=$admin->password_hash?\Yii::$app->security->generatePasswordHash($admin->password_hash):$password;

           // var_dump($admin->password_hash);exit;

            //设置令牌 随机字符串 32位
           // $admin->auth_key=\Yii::$app->security->generateRandomString();
            if ($admin->save()) {
                //提示
                \Yii::$app->session->setFlash('success','编辑成功');
                return $this->redirect(['index']);
            }



        }

        $admin->password_hash=null;
        return $this->render('add',compact('admin'));


    }
}
