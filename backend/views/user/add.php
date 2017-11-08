<?php
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'username')->textInput();
echo $form->field($model,'password_hash')->passwordInput();
echo $form->field($model,'email')->textInput();
echo $form->field($model,'status')->radioList(['1'=>'在线','0'=>'下线']);
echo \yii\bootstrap\Html::submitInput('提交');
\yii\bootstrap\ActiveForm::end();