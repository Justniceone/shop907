<?php

namespace frontend\controllers;

use backend\models\Goods;
use frontend\models\Address;
use frontend\models\Cart;
use frontend\models\Order;
use frontend\models\OrderGoods;
use yii\db\Exception;

class OrderController extends \yii\web\Controller
{
    public $enableCsrfValidation=false;
    public function actionIndex()
    {
        if(\Yii::$app->user->isGuest){
            return $this->redirect(['member/login']);
        }
        //根据登录用户查询地址
        $models=Address::find()->where(['member_id'=>\Yii::$app->user->id])->all();
        //获取购物车商品
        $carts=Cart::find()->where(['member_id'=>\Yii::$app->user->id])->all();
        return $this->renderPartial('check-order',['models'=>$models,'carts'=>$carts]);
    }

    public function actionReceive(){
        //Array ( [address_id] => 2 [delivery] => 2 [pay] => 3 )

        $request=\Yii::$app->request;
        $order=new Order();
        if($request->isPost){
            $order->load($request->post(),'');
            //根据地址表id查询出详细的地址信息

            $address=Address::findOne(['id'=>$request->post('address_id')]);
            $order->member_id=\Yii::$app->user->id;
            $order->name=$address->username;
            $order->provice=$address->province;
            $order->city=$address->city;
            $order->area=$address->area;
            $order->address=$address->address;
            $order->tel=$address->tel;
           // $order->delivery_id=$request->post('delivery');
            $order->delivery_name=Order::$delivery[$order->delivery_id]['name'];
            $order->delivery_price=Order::$delivery[$order->delivery_id]['charge'];
            $order->payment_id=$request->post('pay');
            $order->payment_name=Order::$payment[$order->payment_id]['name'];
            $order->total=0;
            $order->status=2;//0已取消1待付款2待发货3待收货4完成）
            $order->trade_no=rand(100000,999999);//订单号随机数字
            $order->create_time=time();
            //操作数据库之前开启事务,确认库存情况
            $transaction=\Yii::$app->db->beginTransaction();
            try{

                if(!$order->save()){
                    var_dump($order->getErrors());
                }

                //根据购物车商品数量检测商品库存,保证每种商品库存足够
                $carts=Cart::find()->where(['member_id'=>\Yii::$app->user->id])->all();
                //遍历确认数量
                foreach ($carts as $cart){
                    $good=Goods::findOne(['id'=>$cart->goods_id]);

                    if($good->stock < ($cart->amount)){
                        throw new Exception($good->name.'库存不足,停止下单');
                    }
                    $good->stock-=$cart->amount;
                    $good->save();
                    //新的订单商品详情表
                    $order_goods=new OrderGoods();
                    $order_goods->order_id=$order->id;
                    $order_goods->goods_id=$cart->goods_id;
                    $order_goods->goods_name=$good->name;
                    $order_goods->logo=$good->logo;
                    $order_goods->price=$good->shop_price;
                    $order_goods->amount=$cart->amount;
                    $order_goods->total=$cart->amount * $good->shop_price;
                    $order_goods->save();
                    //计算订单总金额
                    $order->total+=$order_goods->total;
                }

                //加上运费
                $order->total+=$order->delivery_price;
                $order->save();
                //订单完成,清空购物车
                Cart::deleteAll(['member_id'=>\Yii::$app->user->id]);

                //提交事务
                $transaction->commit();
                //跳转到订单列表页
                return $this->redirect(['order/order-list']);
            }catch (Exception $exception){
                //库存不够,不能下单,回滚数据
                $transaction->rollBack();
            }
        }
    }

    public function actionOrderList(){

        //根据用户id展示该用户的所有订单
        $orders=Order::find()->where(['member_id'=>\Yii::$app->user->id])->all();
        $order_ids=Order::find()->select(['id'])->where(['member_id'=>\Yii::$app->user->id])->asArray()->column();
       // print_r($order_ids);die;
        //根据订单号查询订单详细信息
        $order_goods=OrderGoods::find()->select(['logo','price'])->where(['in','order_id',$order_ids])->all();
        return $this->renderPartial('order-list',['orders'=>$orders,'order_goods'=>$order_goods]);
    }

    public function actionDelete($id){
        //根据id删除订单
       // Order::findOne(['id'=>$id])->delete();
        //删除订单详情中的商品数据
       // OrderGoods::deleteAll(['order_id'=>$id]);
        return json_encode(['success'=>true,'msg'=>'删除成功']);
    }
}
