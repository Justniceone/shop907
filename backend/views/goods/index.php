<a href="<?=\yii\helpers\Url::to(['goods/add'])?>"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span></a>
<form action="<?=\yii\helpers\Url::to(['goods/index'])?>" method="get">
    <input type="text" name="name" placeholder="商品名称" class="form-inline">&emsp;
    <input type="text" name="sn" placeholder="货号sn"  class="form-inline">&emsp;
    <input type="text" name="sn" placeholder="$"  class="form-inline">&emsp;
    <input type="text" name="tprice" placeholder="到"  class="form-inline">
    <input type="submit" value="搜索" class="btn btn-success ">
</form>
<table class="table table-bordered table-hover">
    <tr class="info">
        <th >id</th>
        <th>商品名称</th>
        <th>商品图片</th>
        <th>商品分类</th>
        <th>品牌分类</th>
        <th>市场价格</th>
        <th>商品价格</th>
        <th>库存</th>
        <th>是否在售</th>
        <th>状态</th>
        <th>排序</th>
        <th>添加时间</th>
        <th>阅览次数</th>
        <th>操作</th>
    </tr>
    <?php foreach($models as $model):?>
        <tr data-id="<?=$model->id?>">
            <td ><?=$model->id?></td>
            <td ><?=$model->name?></td>
            <td><img src="<?=$model->logo?>" style="width: 100px"></td>
            <td><?=$model->goodsCategory->name?></td>
            <td><?=$model->brand->name?></td>
            <td><?=$model->market_price?></td>
            <td><?=$model->shop_price?></td>
            <td><?=$model->stock?></td>
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

