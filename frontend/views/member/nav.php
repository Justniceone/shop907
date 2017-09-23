
<!-- 顶部导航 start -->
<div class="topnav">
    <div class="topnav_bd w990 bc">
        <div class="topnav_left">

        </div>
        <div class="topnav_right fr">
            <ul>
                <li class="line is_guest"><?=Yii::$app->user->identity?Yii::$app->user->identity->username:'游客' ?></li>
                <li>您好，欢迎来到<em>京胖!</em>
                    [<a href="<?=\yii\helpers\Url::to(['member/login'])?>" class="login">登录</a>]
                    <a href="<?=\yii\helpers\Url::to(['member/logout'])?>" class="logout" style="display: none">注销</a>
                    [<a href="<?=\yii\helpers\Url::to(['member/regist'])?>" class="regist">免费注册</a>]
                </li>
                <li class="line">|</li>
                <li>我的订单</li>
                <li class="line">|</li>
                <li>客户服务</li>

            </ul>
        </div>
    </div>
</div>
<!-- 顶部导航 end -->

<div class="navitems fl">
    <ul class="fl">
        <li class="current"><a href="">首页</a></li>
        <li><a href="">电脑频道</a></li>
        <li><a href="">家用电器</a></li>
        <li><a href="">品牌大全</a></li>
        <li><a href="">团购</a></li>
        <li><a href="">积分商城</a></li>
        <li><a href="">夺宝奇兵</a></li>
    </ul>
    <div class="right_corner fl"></div>
</div>

<?php
/**
 * @var $this \yii\web\View
 */
$this->registerJs(new \yii\web\JsExpression(
        <<<JS
     console.log($('.is_guest').text)   ;
     console.log(111)   
JS

));