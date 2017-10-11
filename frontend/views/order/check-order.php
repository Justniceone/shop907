<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <title>填写核对订单信息</title>
    <link rel="stylesheet" href="/style/base.css" type="text/css">
    <link rel="stylesheet" href="/style/global.css" type="text/css">
    <link rel="stylesheet" href="/style/header.css" type="text/css">
    <link rel="stylesheet" href="/style/fillin.css" type="text/css">
    <link rel="stylesheet" href="/style/footer.css" type="text/css">
    <script type="text/javascript" src="/js/jquery-1.8.3.min.js"></script>
    <script type="text/javascript" src="/js/cart2.js"></script>
</head>
<body>
<!-- 顶部导航 start -->
<?php require '../views/member/nav.php'?>
<!-- 顶部导航 end -->

<div style="clear:both;"></div>

<!-- 页面头部 start -->
<div class="header w990 bc mt15">
    <div class="logo w990">
        <h2 class="fl"><a href="index.html"><img src="/images/logo.png" alt="京西商城"></a></h2>
        <div class="flow fr flow2">
            <ul>
                <li>1.我的购物车</li>
                <li class="cur">2.填写核对订单信息</li>
                <li>3.成功提交订单</li>
            </ul>
        </div>
    </div>
</div>
<!-- 页面头部 end -->

<div style="clear:both;"></div>

<!-- 主体部分 start -->
<div class="fillin w990 bc mt15">
    <div class="fillin_hd">
        <h2>填写并核对订单信息</h2>
    </div>

<form action="<?=\yii\helpers\Url::to(['order/receive'])?>" method="post">
    <div class="fillin_bd">

        <!-- 收货人信息  start-->
        <div class="address">
            <h3>收货人信息</h3>
            <div class="address_info">
                <?php foreach ($models as $k=>$model):?>
                <p> <input type="radio" value="<?=$model->id?>" name="address_id" <?=$k==0?'checked':''?>/><?=$model->username?> &emsp;<?=$model->province.$model->city.$model->area.$model->address?></p>
                <?php endforeach;?>
            </div>
        </div>

        <!-- 收货人信息  end-->

        <!-- 配送方式 start -->
        <div class="delivery">
            <h3>送货方式 </h3>
            <div class="delivery_select">
                <table>
                    <thead>
                    <tr>
                        <th class="col1">送货方式</th>
                        <th class="col2">运费</th>
                        <th class="col3">运费标准</th>
                    </tr>
                    </thead>
                    <tbody>

                    <?php foreach (\frontend\models\Order::$delivery as $key=>$value):?>
                    <tr class="cur">
                        <td>
                            <input type="radio" name="delivery_id"  value="<?=$value['id']?>" <?=$key==1?'checked':''?> class="<?=$value['charge']?> money "/><label><?=$value['name']?></label>
                        </td>
                        <td><?=$value['charge']?></td>
                        <td><?=$value['intro']?></td>
                    </tr>

                    <?php endforeach;?>

                    </tbody>
                </table>

            </div>
        </div>
        <!-- 配送方式 end -->

        <!-- 支付方式  start-->
        <div class="pay">
            <h3>支付方式 </h3>


            <div class="pay_select">
                <table>
                    <?php foreach (\frontend\models\Order::$payment as $key=>$item):?>
                    <tr class="cur">
                        <td class="col1"><input type="radio" name="pay" <?=$key==1?'checked':''?> value="<?=$item['id']?>"/><label for=""><?=$item['name']?></label></td>
                        <td class="col2"><?=$item['intro']?></td>
                    </tr>
                    <?php endforeach;?>
                </table>

            </div>
        </div>
        <!-- 支付方式  end-->



        <!-- 商品清单 start -->
        <div class="goods">
            <h3>商品清单</h3>
            <table>
                <thead>
                <tr>
                    <th class="col1">商品</th>
                    <th class="col3">价格</th>
                    <th class="col4">数量</th>
                    <th class="col5">小计</th>
                </tr>
                </thead>
                <tbody>
                <?php $total=0;?>
                <?php foreach ($carts as $k=>$cart):?>
                <tr>
                    <td class="col1"><a href=""><img src="http://admin.yiishop.com<?=$cart->goods->logo?>"/></a>  <strong><a href=""><?=$cart->goods->name?></a></strong></td>
                    <td class="col3"><?=$cart->goods->shop_price?></td>
                    <td class="col4"><?=$cart->amount?></td>
                    <td class="col5"><span>￥<?=$cart->amount * $cart->goods->shop_price?></span></td>
                </tr>
                    <?php $total+=$cart->amount * $cart->goods->shop_price?>
                <?php endforeach;?>

                </tbody>
                <tfoot>
                <tr>
                    <td colspan="5">
                        <ul>
                            <li>
                                <span><?=\frontend\models\Cart::find()->where(['member_id'=>\Yii::$app->user->id])->count()?>件商品，总商品金额：</span>
                                <em>￥<?=$total?></em>
                            </li>
                            <li>
                                <span>返现：</span>
                                <em>-￥0.00</em>
                            </li>
                            <li>
                                <span>运费：</span>
                                <em>￥<span class="fee"></span></em>
                            </li>
                            <li>
                                <span>应付总额：</span>
                                <em>￥<?=$total?></em>
                            </li>
                        </ul>
                    </td>
                </tr>
                </tfoot>
            </table>
        </div>
        <!-- 商品清单 end -->

    </div>

    <div class="fillin_ft">
        <input type="submit" value="提交订单">
        <p>应付总额：<strong>￥<?=$total?>元</strong></p>

    </div>
</div>
</form>
<!-- 主体部分 end -->

<div style="clear:both;"></div>
<!-- 底部版权 start -->
<div class="footer w1210 bc mt15">
    <p class="links">
        <a href="">关于我们</a> |
        <a href="">联系我们</a> |
        <a href="">人才招聘</a> |
        <a href="">商家入驻</a> |
        <a href="">千寻网</a> |
        <a href="">奢侈品网</a> |
        <a href="">广告服务</a> |
        <a href="">移动终端</a> |
        <a href="">友情链接</a> |
        <a href="">销售联盟</a> |
        <a href="">京西论坛</a>
    </p>
    <p class="copyright">
        © 2005-2013 京东网上商城 版权所有，并保留所有权利。  ICP备案证书号:京ICP证070359号
    </p>
    <p class="auth">
        <a href=""><img src="/images/xin.png" alt="" /></a>
        <a href=""><img src="/images/kexin.jpg" alt="" /></a>
        <a href=""><img src="/images/police.jpg" alt="" /></a>
        <a href=""><img src="/images/beian.gif" alt="" /></a>
    </p>
</div>
<!-- 底部版权 end -->
</body>
<script type="text/javascript">
    $('.money').click(function () {
        var money=parseInt($(this).attr('class'));
    $('.fee').text(money);
    })
</script>
</html>
