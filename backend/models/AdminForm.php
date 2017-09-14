<?php
namespace backend\models;
use yii\base\Model;

class AdminForm extends Model{
    public $username;
    public $password_hash;
    public $code;
    public $remember;
    public function rules()
    {
        return [
            [['username','password_hash'],'required'],
            ['code','captcha'],
            ['remember','integer']
        ];
    }
    public function attributeLabels()
    {
        return [
            'username'=>'用户名',
            'password_hash'=>'密码',
            'code'=>'验证码',
            'remember'=>'记住我',
        ];
    }
}