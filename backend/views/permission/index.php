<!--第二步：添加如下 HTML 代码-->
<table id="table_id_example" class="display">
    <thead>
    <tr>
        <th>权限名称</th>
        <th>权限描述</th>
        <th>操作</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($permissions as $permission):?>
    <tr>
        <td><?=$permission->name?></td>
        <td><?=$permission->description?></td>
        <td>
            <?=\yii\bootstrap\Html::a('修改',["permission/edit?name=$permission->name"])?>&emsp;
            <?=\yii\bootstrap\Html::a('删除',["permission/delete?name=$permission->name"])?>
        </td>
    </tr>
    <?php endforeach;?>
    </tbody>
</table>

<a href="<?=\yii\helpers\Url::to(['add'])?>" class="btn btn-primary">添加权限</a>


<!--第三步：初始化Datatables-->
<?php
/**
 * @var $this \yii\web\View
 */
$this->registerCssFile('@web/assets/media/css/jquery.dataTables.css');
$this->registerJsFile('@web/assets/media/js/jquery.js');
$this->registerJsFile('@web/assets/media/js/jquery.dataTables.js',['depends'=>\yii\web\JqueryAsset::className()]);

$this->registerJs(new \yii\web\JsExpression(
        <<<JS
       
$(document).ready( function () {
    $('#table_id_example').DataTable();
} );

JS

))

?>

