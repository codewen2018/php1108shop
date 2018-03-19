<?php
/* @var $this yii\web\View */
?>
<h1>商品分类列表</h1>

<p>
    <?= \yii\bootstrap\Html::a('添加', ['add'], ['class' => 'btn btn-info']) ?>

<table class="table">

    <tr>
        <th>id</th>
        <th>名称</th>
        <th>父Id</th>
        <th>简介</th>
        <th>操作</th>
    </tr>

<?php foreach ($cates as $cate):?>
    <tr class="cate" data-tree="<?=$cate->tree?>" data-lft="<?=$cate->lft?>" data-rgt="<?=$cate->rgt?>">
        <td><?=$cate->id?></td>
        <td><span class="cate-tr glyphicon glyphicon-chevron-down"></span><?=$cate->nameText?></td>
        <td><?=$cate->parent_id?></td>
        <td><?=$cate->intro?></td>
        <td>
            <?=\yii\bootstrap\Html::a("编辑",['edit','id'=>$cate->id],['class'=>'btn btn-success'])?>
            <?=\yii\bootstrap\Html::a("删除",['del','id'=>$cate->id],['class'=>'btn btn-danger'])?>
        </td>

    </tr>
    <?php endforeach;?>
</table>
</p>

<?php
//定义JS代码块
$js=<<<JS
//找到它
$(".cate-tr").click(function() {
  
    //当前对象 点击的那个对象
    var trParent=$(this).parent().parent();
    //当前对象 的tree lft rght
    var treeParent=trParent.attr('data-tree');
    var lftParent=trParent.attr('data-lft');
    var rgtParent=trParent.attr('data-rgt');
    
    //找出所有tr 再一一对比
    $(".cate").each(function(k,v) {
        //每一个的tree lft rgt
        var tree=$(v).attr('data-tree');
        var lft=$(v).attr('data-lft');
        var rgt=$(v).attr('data-rgt');
     
      //和点击的那个对象来对比，找出它的子孙 2<15 "2">"15" 如果+左右出现字符串，就往字符串转，其它所有都往数子转 把这里转成数子
      if (tree==treeParent && lft-0>lftParent && Number(rgt)<rgtParent){
          //找到子孙
           console.log(tree,lft,rgt);
           //隐藏
           $(v).toggle();
      }
    });
    
    console.log(treeParent,lftParent,rgtParent);
    
    $(this).toggleClass('glyphicon-chevron-down');
    $(this).toggleClass('glyphicon-chevron-up');
    console.log(this);
});

  
JS;

//注册JS代码块

$this->registerJs($js);
?>
