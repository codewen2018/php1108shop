<?php

namespace backend\controllers;

use backend\models\Brand;
use yii\helpers\Json;
use yii\web\UploadedFile;
use crazyfd\qiniu\Qiniu;

class BrandController extends \yii\web\Controller
{
    /**
     * 品牌列表
     * @return string
     */
    public function actionIndex()
    {
        //找到所有数据
        $brands=Brand::find()->all();

        return $this->render('index',compact('brands'));
    }

    /**
     * 品牌添加
     * @return string|\yii\web\Response
     */
    public function actionAdd(){

        $model=new Brand();

        //判断POST
        if (\Yii::$app->request->isPost){
            //数据绑定
            $model->load(\Yii::$app->request->post());
            //上传图片
           // $model->img=UploadedFile::getInstance($model,'img');

            //定义一个图片路径
          //  $imgPath="";
            //如果上传了图片，移动图片
          /*  if ($model->img!==null){
             //拼路径
                $imgPath="images/".time().".".$model->img->extension;
                //移动 有坑
                $model->img->saveAs($imgPath,false);

            }*/

            //后台验证
            if ($model->validate()) {
                //把图片路径赋值给logo
              //  $model->logo=$imgPath;
                //保存数据
                if ($model->save()) {
                    //提法
                    \Yii::$app->session->setFlash('success','添加成功');
                    //跳转
                    return $this->redirect(['index']);


                }

            }else{

                //TODO:
                var_dump($model->errors);exit;


            }








        }

        return $this->render('add',compact('model'));



    }

    /**
     * 品牌编辑
     * @param $id 品牌Id
     * @return string|\yii\web\Response
     */
    public function actionEdit($id){

        //找出需要编辑
        $model=Brand::findOne($id);

        //判断POST
        if (\Yii::$app->request->isPost){
            //数据绑定
            $model->load(\Yii::$app->request->post());
            //上传图片
            $model->img=UploadedFile::getInstance($model,'img');

            //定义一个图片路径
            $imgPath="";
            //如果上传了图片，移动图片
            if ($model->img!==null){
                //拼路径
                $imgPath="images/".time().".".$model->img->extension;
                //移动 有坑
                $model->img->saveAs($imgPath,false);

            }

            //后台验证
            if ($model->validate()) {

                //判断图片是否为空
                if ($imgPath){
                    //TODO 删除之前的图片 unlink()

                    //把图片路径赋值给logo
                    $model->logo=$imgPath;


                }
             // $model->logo=$imgPath?:$model->logo;

                //保存数据
                if ($model->save()) {
                    //提法
                    \Yii::$app->session->setFlash('success','添加成功');
                    //跳转
                    return $this->redirect(['index']);


                }

            }else{

                //TODO:
                var_dump($model->errors);exit;


            }








        }

        return $this->render('add',compact('model'));



    }

    /**
     * 品牌删除
     * @param $id 品牌Id
     *
     */
    public function actionDel($id){

        if (Brand::findOne($id)->delete()) {
            //提示
            \Yii::$app->session->setFlash('success','删除成功');

            return $this->redirect(['index']);
        }
    }
    //用处理上传图片
    public function actionUpload(){




      switch (\Yii::$app->params['uploadType']){

          case "local":
              //本地上传
              echo "local";
              break;
          case "qiniu":
              //七牛上传
              echo "qiniu";
              break;


      }
      exit;

        //通过name值得到文件上传对象
        $fileObj=UploadedFile::getInstanceByName('file');
        //移动临时文件到WEB目录
        if ($fileObj!==null){
            //拼路径
            $filePath="images/".time().".".$fileObj->extension;
            //移动
            if ($fileObj->saveAs($filePath,false)) {
                // 正确时， 其中 attachment 指的是保存在数据库中的路径，url 是该图片在web可访问的地址
               // {"code": 0, "url": "http://domain/图片地址", "attachment": "图片地址"}
                //定义一个数组
                $ok=[
                    'code'=>0,
                    'url'=>"/".$filePath,//预览地址
                    "attachment"=>$filePath//图片上传后地址
                ];
                //返回JSON数据
                return json_encode($ok);


            }

        }else{
            // 错误时
          //  {"code": 1, "msg": "error"}
            //定义错误数组
            $result=[
                'code'=>1,
                'msg'=>"error"
            ];

            return Json::encode($result);


        }


    }

    //用处理上传图片
    public function actionQiniuUpload(){
      //  var_dump($_FILES['file']);exit;
        $ak = 'EAd29Qrh05q78_cZhajAWcbB1wYCBLyHLqkanjOG';//应用Id
        $sk = '_R5o3ZZpPJvz8bNGBWO9YWSaNbxIhpsedbiUtHjW';//密钥
        $domain = 'http://p5nv0polm.bkt.clouddn.com/';//地址
        $bucket = 'php1108';//空间名称
        $zone = 'south_china';//区域
        //创建七牛云对象
        $qiniu = new Qiniu($ak, $sk,$domain, $bucket,$zone);
        $key = time();
        //拼路径  123541235132.gif
        $key =$key. strtolower(strrchr($_FILES['file']['name'], '.'));

        //利用七牛云对象上传文件
        $qiniu->uploadFile($_FILES['file']['tmp_name'],$key);
        $url = $qiniu->getLink($key);

        $ok=[
            'code'=>0,
            'url'=>$url,//预览地址
            "attachment"=>$url//图片上传后地址
        ];
        //返回JSON数据
        return json_encode($ok);





    }
}
