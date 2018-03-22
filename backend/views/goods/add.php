<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $good backend\models\Goods */
/* @var $form ActiveForm */
?>
<div class="goods-add">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($good, 'name') ?>
    <?= $form->field($good, 'sn') ?>
    <?= $form->field($good, 'logo')->widget(\manks\FileInput::className(), []) ?>
    <?= $form->field($good, 'images')->widget(\manks\FileInput::className(), [
        'clientOptions' => [
            'pick' => [
                'multiple' => true,
            ],
            // 'server' => Url::to('upload/u2'),
            // 'accept' => [
            // 	'extensions' => 'png',
            // ],
        ]]) ?>
    <?= $form->field($good, 'category_id')->dropDownList($cates, ['prompt' => '请选择分类']) ?>
    <?= $form->field($good, 'brand_id')->dropDownList($brands, ['prompt' => '请选择品牌']) ?>
<?=$form->field($intro,'content')->widget(kucha\ueditor\UEditor::className(),[])?>
    <?= $form->field($good, 'market_price') ?>
    <?= $form->field($good, 'shop_price') ?>
    <?= $form->field($good, 'stock') ?>
    <?= $form->field($good, 'status')->radioList(['禁用', '激活'], ['value' => 1]) ?>
    <?= $form->field($good, 'sort')->textInput(['value' => 100]) ?>


    <div class="form-group">
        <?= Html::submitButton('Submit', ['class' => 'btn btn-primary']) ?>
    </div>
    <?php ActiveForm::end(); ?>

</div><!-- goods-add -->
