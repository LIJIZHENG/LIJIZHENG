<?php
$form=\yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'status',['inline'=>1])->radioList(['1'=>'显示','0'=>'隐藏']);
echo \yii\bootstrap\Html::submitInput('提交');
\yii\bootstrap\ActiveForm::end();