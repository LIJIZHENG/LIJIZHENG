<?php
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'name')->textInput();
echo $form->field($model,'description')->textarea();
echo \yii\bootstrap\Html::submitInput('添加');
\yii\bootstrap\ActiveForm::end();