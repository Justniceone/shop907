<?php
namespace backend\models;

use yii\base\Model;

class PermissionForm extends Model{

    public $name;
    public $description;
    public $status;
    public function rules()
    {
        return [
            [['name','description'],'required'],
            ['name','validateName'],
        ];
    }
    public function attributeLabels()
    {
        return [
            'name'=>'权限名称(路由)',
            'description'=>'描述',
        ];
    }

    public function validateName(){
        //验证不能有相同的权限
        if($this->status){
            $auth = \Yii::$app->authManager;
            if($auth->getPermission($this->name)){
                $this->addError('name','已有相同权限');
            }
        }
    }

}