<?php

namespace frontend\controllers;

use frontend\models\Address;
use yii\helpers\Json;

class AddressController extends \yii\web\Controller
{
    public function actionIndex()
    {
        //查出所有数据
        $addresss=Address::find()->where(['user_id'=>\Yii::$app->user->id])->all();
        return $this->render('index',compact('addresss'));
    }

    public function actionAdd(){


        if (\Yii::$app->request->isPost){
            $address=new Address();
            //绑定
            $address->load(\Yii::$app->request->post());
            //验证
            if ($address->validate()){
                //给user_id赋值
                $address->user_id=\Yii::$app->user->id;
              //给status重新赋值
                if ($address->status===null){

                    $address->status=0;

                }else{
                    //把其它状态设置0
                    Address::updateAll(['status'=>0],['user_id'=>$address->user_id]);

                    $address->status=1;

                }
              //  $address->status=$address->status===null?0:1;

                //保存数据
                if ($address->save()) {

                    $result=[
                        'status'=>1,
                        'msg'=>'操作成功'
                    ];

                    return Json::encode($result);



                }

            }else{

                //提示错误
            }





        }


    }

    public function actionDel($id){

        if (Address::findOne(['id'=>$id,'user_id'=>\Yii::$app->user->id])->delete()) {

            return Json::encode([
                'status'=>1,
                'msg'=>'删除成功'
            ]);
        }
    }

}
