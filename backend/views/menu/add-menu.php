<?php
$form=\yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'label')->textInput();
echo $form->field($model,'sort')->textInput();
echo $form->field($model,'parent_id')->dropDownList(\backend\models\Menu::getMenu());
echo $form->field($model,'url')->dropDownList(\backend\models\Menu::getUrl());
echo $form->field($model,'status')->radioList(['1'=>'正常','0'=>'回收站']);
echo \yii\bootstrap\Html::submitInput('添加');
\yii\bootstrap\ActiveForm::end();