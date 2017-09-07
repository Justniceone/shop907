<?php
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'name')->textInput();
echo $form->field($model,'intro')->textarea();
?>
<img src="<?=$model->logo?>" style='width: 100px'>
<?php
echo $form->field($model,'file')->fileInput();
echo $form->field($model,'sort')->textInput();
echo $form->field($model,'status')->checkbox();
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();
