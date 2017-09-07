<a href="<?=\yii\helpers\Url::to(['brand/add'],['class'=>'btn btn-success'])?>" class="btn btn-info" >添加</a>

<table class="table table-bordered">
    <tr>
        <th>id</th>
        <th>名称</th>
        <th>简介</th>
        <th>图标</th>
        <th>排序</th>
        <th>状态</th>
        <th>操作</th>
    </tr>
    <?php foreach ($models as $model):?>
        <tr>
            <td><?=$model->id?></td>
            <td><?=$model->name?></td>
            <td><?=$model->intro?></td>
            <td><img src="<?=$model->logo?>" style="width: 100px"></td>
            <td><?=$model->sort?></td>
            <td><?=$model->status?'上架':'下架'?></td>
            <td>
                <?=\yii\bootstrap\Html::a('删除',['brand/delete','id'=>$model->id])?>&emsp;
                <?=\yii\bootstrap\Html::a('修改',['brand/edit','id'=>$model->id])?>
                <?=\yii\bootstrap\Html::button('ajax删除',['class'=>'btn btn-danger btn-sm'])?>
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
