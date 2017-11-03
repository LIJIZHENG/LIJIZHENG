<?php
$form=\yii\bootstrap\ActiveForm::begin();
echo $form->field($brand,'name')->textInput()->label('名称');
echo $form->field($brand,'intro')->textarea(['style'=>'height:100px;'])->label('简介');
echo $form->field($brand,'imgFile')->fileInput()->label('LOGO');
echo $form->field($brand,'sort')->textInput()->label('排序');
echo $form->field($brand,'status',['inline'=>1])->radioList(['1'=>'显示','0'=>'隐藏']);
echo \yii\bootstrap\Html::submitInput('提交');
\yii\bootstrap\ActiveForm::end();