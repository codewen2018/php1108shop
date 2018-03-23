<?php
$form=\yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'name')->textInput(['disabled'=>""]);
echo $form->field($model,'description')->textarea();
echo $form->field($model,'permissions')->inline()->checkboxList($persArr);
echo \yii\bootstrap\Html::submitButton("提交",['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();