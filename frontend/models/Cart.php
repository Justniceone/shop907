<?php
namespace frontend\models;

use backend\models\Goods;
use yii\db\ActiveRecord;

class  Cart extends ActiveRecord{

    public function rules()
    {
        return [
            [['goods_id','amount','member_id'],'integer'],
        ];
    }

    public function getGoods(){
        //获取商品名称
        return $this->hasOne(Goods::className(),['id'=>'goods_id']);
    }
}