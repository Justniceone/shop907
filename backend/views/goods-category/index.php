<a href="<?=\yii\helpers\Url::to(['goods-category/add'],['class'=>'btn btn-success'])?>" class="btn btn-info" >添加</a>

<table class="table table-bordered">
    <tr>
        <th>id</th>
        <th>名称</th>
        <th>树id</th>
        <th>lft</th>
        <th>rgt</th>
        <th>deep</th>
        <th>上级id</th>
        <th>简介</th>
        <th>操作</th>
    </tr>
    <?php foreach ($models as $model):?>
        <tr>
            <td><?=$model->id?></td>
            <td><?=$model->name?></td>
            <td><?=$model->tree?></td>
            <td><?=$model->lft?></td>
            <td><?=$model->rgt?></td>
            <td><?=$model->depth?></td>
            <td><?=$model->parent_id?></td>
            <td><?=$model->intro?></td>
            <td>
                <?=\yii\bootstrap\Html::a('删除',['goods-category/delete','id'=>$model->id])?>&emsp;
                <?=\yii\bootstrap\Html::a('修改',['goods-category/edit','id'=>$model->id])?>&emsp;
            </td>
        </tr>
    <?php endforeach;?>
</table>
<?php
echo \yii\widgets\LinkPager::widget([
    'pagination'=>$pager,
    'options'=>['class'=>'pagination-lg pagination'],
]);

?>
