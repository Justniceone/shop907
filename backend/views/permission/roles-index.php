<!--第二步：添加如下 HTML 代码-->
<table id="table_id_example" class="display">
    <thead>
    <tr>
        <th>角色名称</th>
        <th>权限描述</th>
        <th>操作</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($roles as $role):?>
        <tr>
            <td><?=$role->name?></td>
            <td><?=$role->description?></td>
            <td>
                <?=\yii\bootstrap\Html::a('修改',["permission/roles-edit?name=$role->name"])?>&emsp;
                <?=\yii\bootstrap\Html::a('删除',["permission/roles-delete?name=$role->name"])?>
            </td>
        </tr>
    <?php endforeach;?>
    </tbody>
</table>

<a href="<?=\yii\helpers\Url::to(['add-roles'])?>" class="btn btn-primary">添加角色</a>


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

