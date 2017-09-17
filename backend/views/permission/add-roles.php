<?php
$form = \yii\bootstrap\ActiveForm::begin();
//var_dump(\backend\models\RolesForm::roles());die;
echo $form->field($roles_form,'name')->textInput();
echo $form->field($roles_form,'description')->textInput();
echo $form->field($roles_form,'permissions')->checkboxList(\backend\models\RolesForm::roles());
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-primary']);
\yii\bootstrap\ActiveForm::end();