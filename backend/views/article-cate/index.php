<?php
/* @var $this yii\web\View */
?>
<h1>文章分类列表</h1>

<p>
    <?= \yii\bootstrap\Html::a('添加', ['add'], ['class' => 'btn btn-info']) ?>

<table class="table">

    <tr>
        <th>id</th>
        <th>名称</th>
        <th>排序</th>
        <th>状态</th>
        <th>帮助类</th>
        <th>简介</th>
        <th>操作</th>
    </tr>

<?php foreach ($cates as $cate):?>
    <tr>
        <td><?=$cate->id?></td>
        <td><?=$cate->name?></td>
        <td><?=$cate->sort?></td>
        <td><?=$cate->status?></td>
        <td><?=$cate->is_help?></td>
        <td><?=$cate->intro?></td>

        <td>
            <?=\yii\bootstrap\Html::a("编辑",['edit','id'=>$cate->id],['class'=>'btn btn-success'])?>
            <?=\yii\bootstrap\Html::a("删除",['del','id'=>$cate->id],['class'=>'btn btn-danger'])?>


        </td>

    </tr>
    <?php endforeach;?>
</table>
</p>
