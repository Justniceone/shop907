<?php
namespace frontend\models;

use yii\base\Model;

class Login extends Model{
    public $username;
    public $password;
    public function rules()
    {
        return [
            ['username','string'],
            ['password','string'],
        ];
    }
}