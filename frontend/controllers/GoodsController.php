<?php

namespace frontend\controllers;

use backend\models\Goods;
use backend\models\GoodsCategory;
use backend\models\GoodsGallery;
use frontend\models\Cart;
use yii\data\Pagination;
use yii\web\Cookie;

class GoodsController extends \yii\web\Controller
{
    public function actionIndex()
    {
        //获取所有顶级分类数据
        $tops=GoodsCategory::find()->where(['parent_id'=>0])->all();

        return $this->renderPartial('index',['tops'=>$tops]);
    }

    public function actionList($cate_id){
        //商品列表页
        //根据id查询出相应分类
        $category=GoodsCategory::findOne(['id'=>$cate_id]);
        //拼查询条件查询商品信息
        $conditions=Goods::find();
        //分条件
        if($category->depth == 2){
            //属于三级分类,直接显示商品信息
            $conditions->andWhere(['goods_category_id'=>$cate_id]);
        }else{
            //根据分类id获取该分类下所有字分类id
            $ids=$category->children()->select('id')->andwhere(['depth'=>2])->column();
            //获取符合该条件的所有商品数据
            $conditions->andWhere(['in','goods_category_id',$ids]);
        }
        $pager=new Pagination([
            'totalCount'=>$conditions->count(),
            'pageSize'=>10
        ]);
        $lists=$conditions->offset($pager->offset)->limit($pager->limit)->all();
        return $this->renderPartial('list',['lists'=>$lists,'pager'=>$pager]);
    }


    public function actionDetail($id){
        //商品详情

        $good=Goods::findOne(['id'=>$id]);
        //根据goods_id显示该商品相册
        $gallery=GoodsGallery::find()->where(['goods_id'=>$id])->all();
        //根据id获取商品详细信息

        return $this->renderPartial('detail',['good'=>$good,'gallery'=>$gallery]);
    }

    public function actionCart($amount,$id){

        if(\Yii::$app->user->isGuest){
            $cookies=\Yii::$app->request->cookies;
            //必须使用requestcookie获取
            //判断是否有值
            if($cookies->getValue('cart')){
                //存在值直接序列化
                $data=unserialize($cookies->getValue('cart'));
            }else{
                $data=[];
            }
            //检测购物车中是否存在已经添加过的商品
            if(array_key_exists($id,$data)){
                //存在就增加数量
                $data[$id]+=$amount;
            }else{
                $data[$id]=$amount;
            }
            //添加数据到cookie中
            $cookies=\Yii::$app->response->cookies;
            $cookie=new Cookie();//实例化cookie对象,添加属性
            $cookie->name='cart';//设置cookie键名
            $data[$id]=$amount; //将数据保存成[id=>num,id2=>num2]的格式然后序列化保存
            $cookie->value=serialize($data);
            $cookies->add($cookie);

        }else{
            //如果登录,数据直接保存到数据库
            $member_id=\Yii::$app->user->id;
            $cart=new Cart();
            $cart->goods_id=$id;
            $cart->amount=$amount;
            $cart->member_id=$member_id;
            $cart->save();
        }

        //跳转到购物车结算页面
        return $this->redirect(['goods/cart-show']);
    }

    public function actionCartShow(){

        if(\Yii::$app->user->isGuest){
            //没有登录从cookie中获取购物车数据
            $cookies=\Yii::$app->request->cookies;//实例化只读cookie
           // print_r(unserialize($cookies->get('cart')));

            //根据cookie信息查找对应的商品

            $arrays=unserialize($cookies->getValue('cart')); //$arrays=[1=>2,3=>4,5=>6];

            if($arrays){
                $goods=Goods::find()->where(['in','id',array_keys($arrays)])->all();
            }else{
                $goods=[];
        }

        }else{
            //登录从数据库获取商品数据
            $carts=Cart::find()->where(['member_id'=>\Yii::$app->user->id])->asArray()->all();
            $arrays=[];
            foreach ($carts as $cart){
                $arrays[$cart['goods_id']]=$cart['amount'];
            }
            //$arrays=[1=>2,3=>4,5=>6]
            $goods=Goods::find()->where(['in','id',array_keys($arrays)])->all();
        }

        return $this->renderPartial('cart',['goods'=>$goods,'arrays'=>$arrays]);
    }

    public function actionChange($id,$num){
        //获取ajax数据改变cookie中的数量

        //判断用户是否登录
        if(\Yii::$app->user->isGuest){
            $cookies=\Yii::$app->request->cookies;
            //必须使用requestcookie获取
            //判断是否有值
            if($cookies->getValue('cart')){
                //存在值直接序列化
                $data=unserialize($cookies->getValue('cart'));
            }else{
                $data=[];
            }

            //修改为当前数量
            $data[$id] =$num;
            //添加数据到cookie中
            $cookies=\Yii::$app->response->cookies;
            $cookie=new Cookie();//实例化cookie对象,添加属性
            $cookie->name='cart';//设置cookie键名
            //将数据保存成[id=>num,id2=>num2]的格式然后序列化保存
            $cookie->value=serialize($data);
            $cookies->add($cookie);

        }else{

            //登录直接操作数据库
           $model=Cart::findOne(['member_id'=>\Yii::$app->user->id,'goods_id'=>$id]);
           //修改数量
           $model->amount=$num;
           $model->save();
        }

    }

    public static function actionSys(){
        //从cookie中获取购物车数据
        $cookies=\Yii::$app->request->cookies;//实例化只读cookie
        $carts=$cookies->getValue('cart');//获取值 序列化后的字符串
        if($carts){
            $carts=unserialize($carts); //[1=>2,3=>4] 反序列化后的格式
            foreach ($carts as $goods_id=>$amount){
                $cart=Cart::findOne(['goods_id'=>$goods_id,'member_id'=>\Yii::$app->user->id]);
                if($cart){
                    //增加数量
                    $cart->amount +=$amount;
                    $cart->save();
                }else{
                    //添加商品
                    $model=new Cart();
                    $model->goods_id=$goods_id;
                    $model->amount=$amount;
                    $model->member_id=\Yii::$app->user->id;
                    $model->save();
                }
            }
        }

        //清除cookie中的购物车信息
        $cookies=\Yii::$app->response->cookies;
        $cookies->remove('cart');

    }
}

