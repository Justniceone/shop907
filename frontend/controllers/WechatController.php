<?php
namespace frontend\controllers;

use EasyWeChat\Foundation\Application;
use yii\web\Controller;
use EasyWeChat\Payment\Order;
use Endroid\QrCode\QrCode;
class WechatController extends Controller{
    //微信支付功能
    public function actionPay(){
        //1.生成订单
        $attributes = [
            'trade_type'       => 'NATIVE', // JSAPI，NATIVE，APP...//扫码支付使用native
            'body'             => '胖天才手机',
            'detail'           => '胖天才 16G 白色',
            'out_trade_no'     => '1217752501201407034',//订单号trade_no
            'total_fee'        => 998000, // 单位：分
            'notify_url'       => 'http://xxx.com/order-notify', // 支付结果通知网址，如果不设置则会使用配置里的默认地址
            //'openid'           => '', // trade_type=JSAPI，此参数必传，用户在商户appid下的唯一标识，
            // ...
        ];
        $order = new Order($attributes);

        //2.调统一下单api
        $app = new Application(\Yii::$app->params['wechat']);
        $payment = $app->payment;
        $result = $payment->prepare($order);
        //var_dump($result);die;
        if ($result->return_code == 'SUCCESS' && $result->result_code == 'SUCCESS'){
            $prepayId = $result->prepay_id;
            //3.通过结果获取返回的交易支付链接code_url
            $code_url=$result->code_url;    // "weixin://wxpay/bizpayurl?pr=XGAgq7h"
            //二维码地址 /wechat/qr?content=$code_url
        }

        //展示二维码到视图页面
        return $this->renderPartial('pay',['order'=>$order,'code_url'=>$code_url]);
    }

    public function actionQr($content){
        //4.将链接生成二维码图片
        $qrCode = new QrCode($content);
        header('Content-Type: '.$qrCode->getContentType());
        echo $qrCode->writeString();
    }

    public function actionOrderNotify(){

        //5.订单支付情况
        $app = new Application(\Yii::$app->params['wechat']);
        $response = $app->payment->handleNotify(function($notify, $successful){
            // 使用通知里的 "微信支付订单号" 或者 "商户订单号" 去自己的数据库找到订单
            //$order = 查询订单($notify->out_trade_no);
            $order = \frontend\models\Order::findOne(['trade_no'=>$notify->out_trade_no]);
            if (!$order) { // 如果订单不存在
                return 'Order not exist.'; // 告诉微信，我已经处理完了，订单没找到，别再通知我了
            }
            // 如果订单存在
            // 检查订单是否已经更新过支付状态
            if ($order->status != 1) { // 假设订单字段“支付时间”不为空代表已经支付
                return true; // 已经支付成功了就不再更新了
            }
            // 用户是否支付成功
            if ($successful) {
                // 不是已经支付状态则修改为已经支付状态
                //$order->paid_at = time(); // 更新支付时间为当前时间
                $order->status = 2;
                $order->save(); // 保存订单
            } else { // 用户支付失败
                //$order->status = 'paid_fail';
            }

            return true; // 返回处理完成
        });
        return $response;
    }

    //6.查询订单接口
    public function actionSearch(){
        $app = new Application(\Yii::$app->params['wechat']);
        $result = $app->payment->query('123123');//订单号trade_no
        var_dump($result);
    }
}