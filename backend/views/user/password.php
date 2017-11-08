<?php
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'oldPassword_hash')->textInput();
echo $form->field($model,'newPassword_hash')->textInput();
echo $form->field($model,'rePassword_hash')->textInput();
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();