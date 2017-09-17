<?php
namespace backend\models;

use yii\base\Model;

class RolesForm extends Model{
    public $name;
    public $description;
    public $permissions;
    public $status;
    public function rules()
    {
        return [
            [['name','description'],'required'],
            ['permissions','safe'],
            ['name','validateName'],
        ];
    }
    public function attributeLabels()
    {
        return [
            'name'=>'角色名称',
            'description'=>'描述',
            'permissions'=>'权限',
        ];
    }

    public static function roles(){
        //获取所有权限
        $auth = \Yii::$app->authManager;
        $permissions=$auth->getPermissions();
        $roles=[];
        foreach ($permissions as $permission){
            $roles[$permission->name]=$permission->description;
        }
        return $roles;
    }

    public function validateName(){
        //验证不能有相同角色
        if($this->status){
            $auth = \Yii::$app->authManager;
            if($auth->getRole($this->name)){
                $this->addError('name','已有相同角色');
            }
        }
    }

}