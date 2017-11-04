<?php
$form=\yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'name')->textInput()->label('名称');
echo $form->field($model,'intro')->textarea(['style'=>'height:100px;'])->label('简介');
echo $form->field($model,'sort')->textInput()->label('排序');
echo $form->field($model,'status',['inline'=>1])->radioList(['1'=>'显示','0'=>'隐藏']);
echo \yii\bootstrap\Html::submitInput('提交');
\yii\bootstrap\ActiveForm::end();