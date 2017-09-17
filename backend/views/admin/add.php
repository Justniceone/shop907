<?php
$form = \yii\bootstrap\ActiveForm::begin();
print_r($roles);die;
echo $form->field($roles_form,'name')->dropDownList(\backend\models\RolesForm::roles());
echo $form->field($model,'username')->textInput();
echo $form->field($model,'password')->passwordInput();
echo $form->field($model,'email')->textInput();
echo $form->field($model,'status')->checkbox();
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();