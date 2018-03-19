<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\Article */
/* @var $form ActiveForm */
?>
<div class="article-add">

    <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'title') ?>
        <?= $form->field($model, 'intro')->textarea() ?>
        <?= $form->field($model, 'sort')->textInput(['value'=>100]) ?>
        <?= $form->field($model, 'status')->radioList(["禁止",'激活'],['value'=>1]) ?>
        <?= $form->field($model, 'cate_id')->dropDownList($catesArr) ?>

    <?=$form->field($content,'detail')->widget('kucha\ueditor\UEditor',[]);?>

        <div class="form-group">
            <?= Html::submitButton('Submit', ['class' => 'btn btn-primary']) ?>
        </div>
    <?php ActiveForm::end(); ?>

</div><!-- article-add -->
