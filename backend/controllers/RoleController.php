<?php

namespace backend\controllers;

use backend\models\AuthItem;
use Symfony\Component\DomCrawler\Tests\Field\InputFormFieldTest;
use yii\helpers\ArrayHelper;

class RoleController extends \yii\web\Controller
{
    /**
     * 角色列表
     * @return string
     */
    public function actionIndex()
    {
        //1 创建auth对象
        $auth=\Yii::$app->authManager;

        //2 找到所有角色
        $roles=$auth->getRoles();

       // var_dump($pers);exit;

        //3 视图
        return $this->render('index',compact('roles'));
    }

    /**
     * 角色添加
     * @return string|\yii\web\Response
     * @throws \Exception
     */
    public function actionAdd(){
        // 创建模型对象
        $model=new AuthItem();
        //1 创建auth对象
        $auth=\Yii::$app->authManager;
        //得到所有权限
        $pers=$auth->getPermissions();
        $persArr=ArrayHelper::map($pers,'name','description');
      //  var_dump($persArr);exit;

        if ($model->load(\Yii::$app->request->post()) && $model->validate()){

            //var_dump($model->permissions);exit;
            //1 创建auth对象
           // $auth=\Yii::$app->authManager;

            //2 创建角色

            $role=$auth->createRole($model->name);


            //3 设置描述
            $role->description=$model->description;

            //4 角色入库
            if ($auth->add($role)) {


                //判断有没有添加权限
                if ($model->permissions){
                    //给当前角色添加权限   循环取出权限并添加给角色
                    foreach($model->permissions as $perName){
                        //通过权限名称得权限对象
                        $per=$auth->getPermission($perName);
                        //给角色添加权限
                        $auth->addChild($role,$per);

                    }


                }

                //提示
                \Yii::$app->session->setFlash('success','角色'.$model->name.'添加成功');
                //刷新
                return $this->refresh();
            }
        }
else{

            //var_dump($model->errors);exit;
}
        //视图
        return $this->render('add',compact('model','persArr'));





    }

    /**
     * 修改角色
     * @param $name 角色名称
     * @return string|\yii\web\Response
     * @throws \Exception
     * @throws \yii\base\Exception
     */
    public function actionEdit($name){
        // 创建模型对象
        $model=AuthItem::findOne($name);
        //1 创建auth对象
        $auth=\Yii::$app->authManager;
        //得到所有权限
        $pers=$auth->getPermissions();
        $persArr=ArrayHelper::map($pers,'name','description');

        //得到当前角色所对应的所有权限
        $rolePers=$auth->getPermissionsByRole($name);

        //取数组中所有key值组成新的一维数组
       // var_dump(array_keys($rolePers));exit;

        $model->permissions=array_keys($rolePers);
        //  var_dump($persArr);exit;

        if ($model->load(\Yii::$app->request->post()) && $model->validate()){

            //var_dump($model->permissions);exit;
            //1 创建auth对象
            // $auth=\Yii::$app->authManager;

            //2 得到角色

            $role=$auth->getRole($model->name);


            //3 设置描述
            $role->description=$model->description;

            //4 更新角色
            if ($auth->update($model->name,$role)) {

                //删除当前角色对应的所有权限
                $auth->removeChildren($role);

                //判断有没有添加权限
                if ($model->permissions){
                    //给当前角色添加权限   循环取出权限并添加给角色
                    foreach($model->permissions as $perName){
                        //通过权限名称得权限对象
                        $per=$auth->getPermission($perName);
                        //给角色添加权限
                        $auth->addChild($role,$per);

                    }


                }

                //提示
                \Yii::$app->session->setFlash('success','角色'.$model->name.'添加成功');
                //刷新
                return $this->redirect(['index']);
            }
        }
        else{

            //var_dump($model->errors);exit;
        }


        //视图
        return $this->render('edit',compact('model','persArr'));





    }

    /**
     * 角色删除
     * @param $name 角色名称
     */
    public function actionDel($name){
        //1 创建auth对象
        $auth=\Yii::$app->authManager;

        //2 找到角色
        $role=$auth->getRole($name);

        //3 干掉它
        if ($auth->remove($role)) {
            \Yii::$app->session->setFlash('success','删除'.$name.'成功');
            return $this->redirect(['index']);
        }

    }
    //把用户添加一个角色
    public function actionAdminRole($roleName,$id){

        //实例化组件对象
        $auth=\Yii::$app->authManager;
        //1 通过角色名称找出角色对象
        $role=$auth->getRole($roleName);
        //2 把用户指派给角色
        var_dump($auth->assign($role,$id));

    }
    //判断当前登录用户有没有权限
    public function actionCheck(){

        //检测当前用户有没有权限
        var_dump(\Yii::$app->user->can('goods/add'));




    }

}
