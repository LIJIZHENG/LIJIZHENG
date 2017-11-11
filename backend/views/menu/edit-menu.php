<?php
$form=\yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'label')->textInput();
echo $form->field($model,'sort')->textInput();
echo $form->field($model,'parent_id')->dropDownList(\backend\models\Menu::getMenu());
echo $form->field($model,'url')->dropDownList(\backend\models\Menu::getMenu());
echo \yii\bootstrap\Html::submitInput('修改');
\yii\bootstrap\ActiveForm::end();