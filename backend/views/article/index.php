<?php
/* @var $this yii\web\View */
?>
<h1>文章列表</h1>

<p>
    <?= \yii\bootstrap\Html::a('添加', ['add'], ['class' => 'btn btn-info']) ?>

<table class="table">

    <tr>
        <th>id</th>
        <th>标题</th>
        <th>分类</th>
        <th>排序</th>
        <th>状态</th>
        <th>简介</th>
        <th>创建时间</th>
        <th>操作</th>
    </tr>

<?php foreach ($articles as $article):?>
    <tr>
        <td><?=$article->id?></td>
        <td><?=$article->title?></td>
        <td><?=$article->cate->name?></td>
        <td><?=$article->sort?></td>
        <td><?=$article->status?></td>
        <td><?=$article->intro?></td>
        <td><?=date("Ymd H:i:s",$article->create_time)?></td>

        <td>
            <?=\yii\bootstrap\Html::a("编辑",['edit','id'=>$article->id],['class'=>'btn btn-success'])?>
            <?=\yii\bootstrap\Html::a("删除",['del','id'=>$article->id],['class'=>'btn btn-danger'])?>


        </td>

    </tr>
    <?php endforeach;?>
</table>
</p>
