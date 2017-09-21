<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "address".
 *
 * @property string $id
 * @property string $address
 * @property integer $member_id
 */
class Address extends \yii\db\ActiveRecord
{
    public $cmbProvince;
    public $cmbCity;
    public $cmbArea;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'address';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['address', 'username'], 'required'],
            [['member_id'], 'integer'],
            [['address'], 'string', 'max' => 255],
            ['is_default','integer'],
            [['cmbProvince','cmbCity','cmbArea','tel'],'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'address' => 'Address',
            'member_id' => 'Member ID',
        ];
    }

    public static function Address(){
        //根据id获取所有地址
        $id=Yii::$app->user->id;
        $address=self::find()->where(['member_id'=>$id])->all();
        return $address;
    }

    public static function AddressExcept(){
        //显示除了正在修改的地址
        $id=Yii::$app->request->get('id');
        $member_id=Yii::$app->user->id;
        $address=self::find()->where(['member_id'=>$member_id])->andWhere(['!=','id',$id])->all();
        return $address;
    }
}
