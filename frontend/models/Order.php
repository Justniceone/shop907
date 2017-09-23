<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "order".
 *
 * @property integer $id
 * @property integer $member_id
 * @property string $name
 * @property string $provice
 * @property string $city
 * @property string $area
 * @property string $address
 * @property string $tel
 * @property integer $delivery_id
 * @property string $delivery_name
 * @property double $delivery_price
 * @property integer $payment_id
 * @property string $payment_name
 * @property string $total
 * @property integer $status
 * @property string $trade_no
 * @property integer $create_time
 */
class Order extends \yii\db\ActiveRecord
{
    //定义配送方式
    public static $delivery=[
        1=>['name'=>'申通','charge'=>15,'intro'=>'服务好,速度快','id'=>1],
        2=>['name'=>'中通','charge'=>15,'intro'=>'誉满中国,通达天下','id'=>2],
        3=>['name'=>'顺丰','charge'=>25,'intro'=>'飞机空运','id'=>3],
        4=>['name'=>'韵达','charge'=>10,'intro'=>'速度慢,服务差','id'=>4],
    ];
    //定义支付方式
    public static $payment=[
        1=>['name'=>'微信支付','intro'=>'直接扫码付款','id'=>1],
        2=>['name'=>'支付宝支付','intro'=>'直接扫码付款','id'=>2],
        3=>['name'=>'epay支付','intro'=>'方便,快捷','id'=>3],
        4=>['name'=>'银联支付','intro'=>'支持绝大数银行借记卡及部分银行信用卡','id'=>4],
    ];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'order';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['member_id', 'delivery_id', 'payment_id', 'status'], 'integer'],
            [['delivery_price', 'total'], 'number'],
            [['name'], 'string', 'max' => 50],
            [['provice', 'city', 'area'], 'string', 'max' => 20],
            [['address', 'payment_name'], 'string', 'max' => 255],
            [['tel', 'delivery_name'], 'string', 'max' => 11],
            [['create_time', 'trade_no'],'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'member_id' => '用户id',
            'name' => '收货人',
            'provice' => '省',
            'city' => '市',
            'area' => '县',
            'address' => '详细地址',
            'tel' => '电话号码',
            'delivery_id' => '配送方式id',
            'delivery_name' => '配送方式名称',
            'delivery_price' => '配送方式价格',
            'payment_id' => '支付方式id',
            'payment_name' => '支付方式名称',
            'total' => '订单金额',
            'status' => '订单状态(0已取消1待付款2待发货3待收货4完成)',
            'trade_no' => '第三方支付交易号',
            'create_time' => '创建时间',
        ];
    }
}
