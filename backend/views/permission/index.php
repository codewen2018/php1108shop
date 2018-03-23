<?php
/* @var $this yii\web\View */
?>
<h1>权限列表</h1>

<p>
    <?= \yii\bootstrap\Html::a('添加', ['add'], ['class' => 'btn btn-info']) ?>

<table class="table">

    <tr>

        <th>名称</th>
        <th>简介</th>
        <th>操作</th>
    </tr>

<?php foreach ($pers as $per):?>
    <tr>
        <td><?=strpos($per->name,'/')!==false?"----":""?><?=$per->name?></td>
        <td><?=$per->description?></td>

        <td>
            <?=\yii\bootstrap\Html::a("编辑",['edit','name'=>$per->name],['class'=>'btn btn-success'])?>
            <?=\yii\bootstrap\Html::a("删除",['del','name'=>$per->name],['class'=>'btn btn-danger'])?>


        </td>

    </tr>
    <?php endforeach;?>
</table>
</p>
