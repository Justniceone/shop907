<table class="table table-bordered">
    <tr>
        <td>内容</td>
    </tr>
    <tr>
        <td><?=$model->content?></td>
    </tr>
</table>
<?php
echo \yii\helpers\Html::a('返回',\yii\helpers\Url::to(['article/index']),['class'=>'btn btn-info']);