<?php
namespace backend\models;
use yii\base\Model;

class AdminForm extends Model{
    public $username;
    public $password_hash;
    public $code;
    public $remember;

    public $new_password;
    public $re_password;

    public function rules()
    {
        return [
            [['username','password_hash'],'required'],
            ['code','captcha'],
            ['remember','integer'],
            [['new_password','re_password'],'string'],
        ];
    }
    public function attributeLabels()
    {
        return [
            'username'=>'用户名',
            'password_hash'=>'密码',
            'code'=>'验证码',
            'remember'=>'记住我',
            'new_password'=>'新密码',
            're_password'=>'确认新密码',
        ];
    }
}