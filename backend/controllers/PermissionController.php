<?php

namespace backend\controllers;

use backend\models\AuthItem;

class PermissionController extends \yii\web\Controller
{
    /**
     * 权限列表
     * @return string
     */
    public function actionIndex()
    {
        //1 创建auth对象
        $auth=\Yii::$app->authManager;

        //2 找到所有权限
        $pers=$auth->getPermissions();

       // var_dump($pers);exit;

        //3 视图
        return $this->render('index',compact('pers'));
    }

    /**
     * 权限添加
     * @return string|\yii\web\Response
     * @throws \Exception
     */
    public function actionAdd(){
        // 创建模型对象
        $model=new AuthItem();

        if ($model->load(\Yii::$app->request->post()) && $model->validate()){
            //1 创建auth对象
            $auth=\Yii::$app->authManager;

            //2 创建权限

            $per=$auth->createPermission($model->name);


            //3 设置描述
            $per->description=$model->description;

            //4 权限入库
            if ($auth->add($per)) {
                //提示
                \Yii::$app->session->setFlash('success','权限'.$model->name.'添加成功');
                //刷新
                return $this->refresh();
            }
        }
else{

            //var_dump($model->errors);exit;
}
        //视图
        return $this->render('add',compact('model'));





    }

    /**
     * 权限编辑
     * @param $name 权限名称
     * @return string|\yii\web\Response
     * @throws \Exception
     */
    public function actionEdit($name){
        // 创建模型对象
        $model=AuthItem::findOne($name);

        if ($model->load(\Yii::$app->request->post()) && $model->validate()){
            //1 创建auth对象
            $auth=\Yii::$app->authManager;

            //2 得到权限

            $per=$auth->getPermission($model->name);


            //3 设置描述
            $per->description=$model->description;

            //4 权限入库
            if ($auth->update($model->name,$per)) {
                //提示
                \Yii::$app->session->setFlash('success','修改'.$model->name.'添加成功');
                //刷新
                return $this->refresh();
            }
        }
        else{

            //var_dump($model->errors);exit;
        }
        //视图
        return $this->render('edit',compact('model'));





    }

    /**
     * 权限删除
     * @param $name 权限名称
     */
    public function actionDel($name){
        //1 创建auth对象
        $auth=\Yii::$app->authManager;

        //2 找到权限
        $per=$auth->getPermission($name);

        //3 干掉它
        if ($auth->remove($per)) {
            \Yii::$app->session->setFlash('success','删除'.$name.'成功');
            return $this->redirect(['index']);
        }

    }

}
