<?php
$form = \yii\bootstrap\ActiveForm::begin();
//var_dump(\backend\models\RolesForm::GetRoles());
echo $form->field($roles_form,'name')->checkboxList(\backend\models\RolesForm::GetRoles());
echo $form->field($model,'username')->textInput();
echo $form->field($model,'password')->passwordInput();
echo $form->field($model,'email')->textInput();
echo $form->field($model,'status')->checkbox();
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();