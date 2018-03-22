<?php
/* @var $this yii\web\View */
/* @var $good backend\models\Goods */
?>
<h1>商品列表</h1>



<p>

    <?= \yii\bootstrap\Html::a('添加', ['add'], ['class' => 'btn btn-info pull-left']) ?>
<form class="form-inline pull-right">
    <select class="form-control" name="status">
        <option>请选择</option>
        <option value="0" <?=Yii::$app->request->get('status')==="0"?"selected":""?>>禁用</option>
        <option value="1" <?=Yii::$app->request->get('status')==="1"?"selected":""?>>激活</option>
    </select>
    <div class="form-group">

        <input type="text" class="form-control" id="minPrice" placeholder="最低价" name="minPrice" size="5" value="<?=Yii::$app->request->get('minPrice')?>">
    </div>
    -
    <div class="form-group">
        <input type="text" class="form-control" id="maxPrice" placeholder="最高价" name="maxPrice" size="5" value="<?=Yii::$app->request->get('maxPrice')?>">
    </div>

    <div class="form-group">
        <input type="text" class="form-control" id="keyword" placeholder="名称或货号" name="keyword" size="10" value="<?=Yii::$app->request->get('keyword')?>">
    </div>
    <button type="submit" class="btn btn-primary"><span class="glyphicon glyphicon-search"></span></button>
</form>
<table class="table">

    <tr>
        <th>id</th>
        <th>名称</th>
        <th>货号</th>
        <th>Logo</th>
        <th>分类</th>
        <th>品牌</th>
        <th>市场价</th>
        <th>本店价</th>
        <th>库存</th>
        <th>状态</th>
        <th>排序</th>
        <th>创建时间</th>
        <th>操作</th>
    </tr>

<?php foreach ($goods as $good):?>
    <tr>
        <td><?=$good->id?></td>
        <td><?=$good->name?></td>
        <td><?=$good->sn?></td>
        <td><?=\yii\bootstrap\Html::img($good->logo,['height'=>40])?></td>
        <td><?=$good->category_id?></td>
        <td><?=$good->brand_id?></td>
        <td><?=$good->market_price?></td>
        <td><?=$good->shop_price?></td>
        <td><?=$good->stock?></td>
        <td><?=$good->status?></td>
        <td><?=$good->sort?></td>

        <td><?=date("Ymd H:i:s",$good->create_at)?></td>

        <td>
            <?=\yii\bootstrap\Html::a("编辑",['edit','id'=>$good->id],['class'=>'btn btn-success'])?>
            <?=\yii\bootstrap\Html::a("删除",['del','id'=>$good->id],['class'=>'btn btn-danger'])?>


        </td>

    </tr>
    <?php endforeach;?>
</table>
<?=\yii\widgets\LinkPager::widget([
    'pagination' => $pagination,
])?>
</p>
