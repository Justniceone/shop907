<?php
$form= \yii\bootstrap\ActiveForm::begin();
echo $form->field($formmodel,'username')->textInput();
echo $form->field($formmodel,'password_hash')->passwordInput();
echo $form->field($formmodel,'code')->widget(\yii\captcha\Captcha::className(),[]);
echo $form->field($formmodel,'remember')->checkbox([0=>'不记住',1=>'记住我']);
echo \yii\bootstrap\Html::submitButton('登录',['class'=>'btn btn-success']);
\yii\bootstrap\ActiveForm::end();