
<!-- 顶部导航 start -->
<div class="topnav">
    <div class="topnav_bd w990 bc">
        <div class="topnav_left">

        </div>
        <div class="topnav_right fr">
            <ul>
                <li class="line is_guest"><?=Yii::$app->user->identity?Yii::$app->user->identity->username:'游客' ?></li>
                <li>您好，欢迎来到<em>京胖!</em>
                    <?php
                        if(Yii::$app->user->isGuest){
                            echo '['.\yii\bootstrap\Html::a('登录',['member/login']).']';
                            echo '['.\yii\bootstrap\Html::a('免费注册',['member/regist']).']';
                        }else{
                            echo '['.\yii\bootstrap\Html::a('注销',['member/logout']).']';
                        }
                    ?>

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
