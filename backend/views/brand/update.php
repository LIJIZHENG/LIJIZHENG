<?php
$form=\yii\bootstrap\ActiveForm::begin();
echo $form->field($brand,'status',['inline'=>1])->radioList(['1'=>'显示','0'=>'隐藏']);
echo $form->field($brand,'imgFile')->fileInput()->label('LOGO');
echo \yii\bootstrap\Html::submitInput('提交');
\yii\bootstrap\ActiveForm::end();