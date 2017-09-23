<div class="cat_bd">

    <?php foreach ($tops as $top):?>
        <div class="cat">
            <h3><a href="<?=\yii\helpers\Url::to(['goods/list?cate_id='.$top->id])?>"><?=$top->name?></a><b></b></h3>
            <div class="cat_detail none">
                <?php foreach ($top->children(1)->all() as $child):?>
                    <dl class="">
                        <dt><a href="<?=\yii\helpers\Url::to(['goods/list?cate_id='.$child->id])?>"><?=$child->name?></a></dt>
                        <?php foreach ($child->children()->all() as $son):?>
                            <dd>
                                <a href="<?=\yii\helpers\Url::to(['goods/list?cate_id='.$son->id])?>"><?=$son->name?></a>
                            </dd>
                        <?php endforeach;?>
                    </dl>
                <?php endforeach;?>
            </div>
        </div>
    <?php endforeach;?>

</div>