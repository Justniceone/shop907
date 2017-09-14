<a href="<?=\yii\helpers\Url::to(['admin/add'])?>"><span class="glyphicon glyphicon-plus btn-lg" aria-hidden="true"></span></a>
<a href="<?=\yii\helpers\Url::to(['admin/login'])?>" class="btn btn-success">登录</a>
<a href="<?=\yii\helpers\Url::to(['admin/logout'])?>" class="btn btn-info">安全退出</a>
<a href="<?=\yii\helpers\Url::to(['admin/change'])?>" class="btn btn-warning">修改密码</a>

<table class="table table-bordered table-hover">
    <tr class="info">
        <th >id</th>
        <th>用户名</th>
        <th>邮箱</th>
        <th>状态</th>
        <th>创建时间</th>
        <th>最后更新时间</th>
        <th>最后登录时间</th>
        <th>最后登录IP</th>
        <th>操作</th>
    </tr>
    <?php foreach($models as $model):?>
        <tr data-id="<?=$model->id?>">
            <td ><?=$model->id?></td>
            <td ><?=$model->username?></td>
            <td><?=$model->email?></td>
            <td><?=$model->status?'正常':'锁定'?></td>
            <td><?=date('Y/m/d',$model->created_at)?></td>
            <td><?=date('Y/m/d H:i:s',$model->updated_at)?></td>
            <td><?=date('Y/m/d H:i:s',$model->last_login_time)?></td>
            <td><?=$model->last_login_ip?$model->last_login_ip:'未登录过'?></td>
            <td>
                <a href="<?=\yii\helpers\Url::to(['admin/delete','id'=>$model->id])?>"><span class="glyphicon glyphicon-trash btn-lg"></span></a>
                <a href="<?=\yii\helpers\Url::to(['admin/edit','id'=>$model->id])?>"<span class="glyphicon glyphicon-cog btn-lg" aria-hidden="true"></span></a>
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

