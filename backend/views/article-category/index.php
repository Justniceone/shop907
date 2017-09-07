<a href="<?=\yii\helpers\Url::to(['article-category/add'],['class'=>'btn btn-success'])?>" class="btn btn-info" >添加</a>
<table class="table table-bordered">
    <tr>
        <th>id</th>
        <th>名称</th>
        <th>简介</th>
        <th>排序</th>
        <th>状态</th>
        <th>操作</th>
    </tr>
    <?php foreach ($models as $model):?>
        <tr>
            <td><?=$model->id?></td>
            <td><?=$model->name?></td>
            <td><?=$model->intro?></td>
            <td><?=$model->sort?></td>
            <td><?=$model->status?'上架':'下架'?></td>
            <td>
                <?=\yii\bootstrap\Html::a('删除',['article-category/delete','id'=>$model->id])?>&emsp;
                <?=\yii\bootstrap\Html::a('修改',['article-category/edit','id'=>$model->id])?>
            </td>
        </tr>
    <?php endforeach;?>
</table>
<?php
echo \yii\widgets\LinkPager::widget([
    'pagination'=>$pager,
    'options'=>['class'=>'pagination-lg pagination'],
]);