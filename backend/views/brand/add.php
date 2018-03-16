<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\Brand */
/* @var $form ActiveForm */
?>
<div class="brand-add">

    <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'name') ?>
        <?= $form->field($model, 'logo')->widget(\manks\FileInput::className(),[]) ?>
        <?= $form->field($model, 'sort') ?>
        <?= $form->field($model, 'status')->inline()->radioList(["禁用",'激活'],['value'=>1])?>
        <?= $form->field($model, 'intro')->textarea() ?>
    
        <div class="form-group">
            <?= Html::submitButton('Submit', ['class' => 'btn btn-primary']) ?>
        </div>
    <?php ActiveForm::end(); ?>

</div><!-- brand-add -->
