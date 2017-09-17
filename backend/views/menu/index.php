<h1>菜单列表</h1>
<?php
//print_r($permissions);
echo \yii\bootstrap\Html::a('添加',['menu/add'],['class'=>'btn btn-default']);

?>

<table class="table table-bordered border-hover">
    <tr>
        <th>名称</th>
        <th>路由</th>
        <th>操作</th>
    </tr>
    <?php foreach ($menus as $menu):?>
        <tr>
            <td>
                <?=str_repeat('&emsp;&emsp;&emsp;',($menu->parent_id?1:0)).$menu->name?>
            </td>
            <td>
                <?=$menu->url?>
            </td>
            <td class="<?=$menu->id?>">
                <?=\yii\bootstrap\Html::a('修改',['menu/edit?id='.$menu->id])?>
                <?=\yii\bootstrap\Html::a('删除','javascript:;',['class'=>'delete'])?>
            </td>
        </tr>
    <?php endforeach;?>
</table>

<?php
/**
 * @var $this \yii\web\View
 */
//点击删除发送ajax请求删除数据
$url=\yii\helpers\Url::to(['menu/delete']);
$this->registerJs(new \yii\web\JsExpression(
        <<<JS
        
        $('.delete').click(function() {
            var id=$(this).closest('td').attr('class');
            var tr=$(this).closest('tr');
            $.getJSON("{$url}",{'id':id},function(data) {
              console.log(data);
              if(data.success){
                  //移除tr
                  tr.fadeOut('slow');
              }
            })
        })
JS

));