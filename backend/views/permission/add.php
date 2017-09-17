<?php
//var_dump($permission_form);die;
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($permission_form,'name')->textInput();
echo $form->field($permission_form,'description')->textInput();
echo \yii\bootstrap\Html::submitButton('确认',['class'=>'btn btn-default']);
\yii\bootstrap\ActiveForm::end();