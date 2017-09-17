<?php
$form=\yii\bootstrap\ActiveForm::begin();
echo $form->field($FormModel,'password_hash')->passwordInput();
echo $form->field($FormModel,'new_password')->passwordInput();
echo $form->field($FormModel,'re_password')->passwordInput();
echo \yii\bootstrap\Html::submitButton('确认修改',['class'=>'btn btn-default']);
\yii\bootstrap\ActiveForm::end();