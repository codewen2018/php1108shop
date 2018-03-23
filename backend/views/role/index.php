<?php
/* @var $this yii\web\View */
?>
<h1>角色列表</h1>

<p>
    <?= \yii\bootstrap\Html::a('添加', ['add'], ['class' => 'btn btn-info']) ?>

<table class="table">

    <tr>

        <th>名称</th>
        <th>简介</th>
        <th>权限</th>
        <th>操作</th>
    </tr>

<?php foreach ($roles as $role):?>
    <tr>
        <td><?=strpos($role->name,'/')!==false?"----":""?><?=$role->name?></td>
        <td><?=$role->description?></td>
        <td>
            <?php
            //得到当前角色所对应的所有权限
            $auth=Yii::$app->authManager;

            //通过当前的角色名得到所有权限
            $pers=$auth->getPermissionsByRole($role->name);

            $html="";
            //var_dump($pers);
            foreach ($pers as $per){

                $html.= $per->description.",";

            }
            //去掉最后面的,
            $html=trim($html,',');
            echo  $html;





            ?>


        </td>

        <td>
            <?=\yii\bootstrap\Html::a("编辑",['edit','name'=>$role->name],['class'=>'btn btn-success'])?>
            <?=\yii\bootstrap\Html::a("删除",['del','name'=>$role->name],['class'=>'btn btn-danger'])?>


        </td>

    </tr>
    <?php endforeach;?>
</table>
</p>
