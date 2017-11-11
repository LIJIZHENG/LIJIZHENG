<?php
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'username')->textInput();
echo $form->field($model,'password_hash')->hiddenInput();
echo $form->field($model,'email')->textInput();
echo $form->field($model,'status')->radioList(['1'=>'在线','0'=>'下线']);
echo $form->field($model,'role',['inline'=>1])->checkboxList($role);
echo \yii\bootstrap\Html::submitInput('提交');
\yii\bootstrap\ActiveForm::end();