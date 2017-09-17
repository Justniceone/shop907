<?php
$roles=\backend\models\RolesForm::roles();
\yii\helpers\ArrayHelper::merge([0=>'选择'],$roles);
//print_r($roles);
print_r(\backend\models\Menu::menu());
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'name')->textInput();
echo $form->field($model,'parent_id')->dropDownList(\yii\helpers\ArrayHelper::merge([0=>'顶级分类'],\backend\models\Menu::menu()));
echo $form->field($model,'url')->dropDownList( \yii\helpers\ArrayHelper::merge([0=>'顶级分类请选此项'],$roles));
echo $form->field($model,'sort')->textInput();
echo \yii\bootstrap\Html::submitButton('确认',['class'=>'btn btn-default']);
\yii\bootstrap\ActiveForm::end();