<?php
/* @var $this yii\web\View */
?>
<h1>品牌列表</h1>

<p>
    <?= \yii\bootstrap\Html::a('添加', ['add'], ['class' => 'btn btn-info']) ?>

<table class="table">

    <tr>
        <th>id</th>
        <th>名称</th>
        <th>图像</th>
        <th>排序</th>
        <th>状态</th>
        <th>简介</th>
        <th>操作</th>
    </tr>

<?php foreach ($brands as $brand):?>
    <tr>
        <td><?=$brand->id?></td>
        <td><?=$brand->name?></td>
        <td><?php
            $imgPath=strpos($brand->logo,"http://")===false?"/".$brand->logo:$brand->logo;
            echo \yii\bootstrap\Html::img($imgPath,['height'=>40]);
            //判断是七牛云还是本地 本地加"/"
         /*   if (strpos($brand->logo,"ttp://")){
                echo \yii\bootstrap\Html::img($brand->logo);
            }else{
                echo \yii\bootstrap\Html::img("/".$brand->logo);
            }*/


            ?></td>
      <!--  <td><?/*=\yii\bootstrap\Html::img(strpos($brand->logo,"ttp://")?$brand->logo:"/".$brand->logo,['height'=>40]);*/?></td>-->
        <td><?=$brand->sort?></td>
        <td><?=$brand->status?></td>
        <td><?=$brand->intro?></td>

        <td>
            <?=\yii\bootstrap\Html::a("编辑",['edit','id'=>$brand->id],['class'=>'btn btn-success'])?>
            <?=\yii\bootstrap\Html::a("删除",['del','id'=>$brand->id],['class'=>'btn btn-danger'])?>


        </td>

    </tr>
    <?php endforeach;?>
</table>
</p>
