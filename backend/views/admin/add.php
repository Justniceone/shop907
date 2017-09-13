<a href="<?=\yii\helpers\Url::to(['goods/add'])?>"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span></a>

<table class="table table-bordered table-hover">
    <tr class="info">
        <th >id</th>
        <th>用户名</th>
        <th>auth_key</th>
        <th>加密密码</th>
        <th>邮箱</th>
        <th>状态</th>
        <th>创建时间</th>
        <th>更新时间</th>
        <th>最后登录时间</th>
        <th>最后登录IP</th>
        <th>操作</th>
    </tr>
    <?php foreach($models as $model):?>
        <tr data-id="<?=$model->id?>">
            <td ><?=$model->id?></td>
            <td ><?=$model->username?></td>
            <td><img src="<?=$model->auth_key?>" style="width: 100px"></td>
            <td><?=$model->password_hash?></td>
            <td><?=$model->email?></td>
            <td><?=$model->status?></td>
            <td><?=$model->create_at?></td>
            <td><?=$model->update_at?></td>
            <td><?=$model->is_on_sale?'在售':'下架'?></td>
            <td><?=$model->status?'正常':'回收站'?></td>
            <td><?=$model->sort?></td>
            <td><?=date('Y/m/d H:i:s',$model->create_time)?></td>
            <td><?=$model->view_times?></td>
            <td>
                <a href="<?=\yii\helpers\Url::to(['goods/del','id'=>$model->id])?>"><span class="glyphicon glyphicon-trash"></span></a>
                <a href="<?=\yii\helpers\Url::to(['goods/edit','id'=>$model->id])?>"<span class="glyphicon glyphicon-cog" aria-hidden="true"></span></a>
                <a href="<?=\yii\helpers\Url::to(['goods/check','id'=>$model->id])?>"><span class="glyphicon glyphicon-film"></span></a>
            </td>
        </tr>
    <?php endforeach;?>
</table>

<?php
//分页工具条
echo \yii\widgets\LinkPager::widget([
    'pagination'=>$pager,
    'nextPageLabel'=>'下一页',
    'prevPageLabel'=>'上一页'
]);

