<?php
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'article_category_id')->dropDownList($array);
echo $form->field($model,'name')->textInput();
echo $form->field($model,'intro')->textarea();
echo $form->field($model,'sort')->textInput();
echo $form->field($content,'content')->textarea(['rows'=>10]);
echo $form->field($model,'status')->checkbox();
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();
