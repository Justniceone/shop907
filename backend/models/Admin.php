<?php

namespace backend\models;

use Yii;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "admin".
 *
 * @property integer $id
 * @property string $username
 * @property string $auth_key
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $last_login_time
 * @property string $last_login_ip
 */
class Admin extends \yii\db\ActiveRecord implements IdentityInterface
{
    public $code;//保存验证码
    public $password;//保存明文密码
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'admin';
    }

    public function beforeSave($insert)
    {
        //新增数据保存之前自动执行
        if($insert){
            //生成随机字符串保存到auth_key
            $this->auth_key=\Yii::$app->security->generateRandomString();
            //密码加密
            $this->password_hash=\Yii::$app->security->generatePasswordHash($this->password);
            //保存当前时间为创建时间
            $this->created_at=time();
        }
        else{
            //判断是否需要修改密码

            if($this->password){
                $this->password_hash=\Yii::$app->security->generatePasswordHash($this->password);
                //更新author_key
                  $this->auth_key=\Yii::$app->security->generateRandomString();
            }

            //添加更新时间
            $this->updated_at=time();
        }
        return parent::beforeSave($insert); // TODO: Change the autogenerated stub
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'email', ], 'required'],
            ['password','required','on'=>'add'],//在add状态下才生效
            ['password','string'],//在任何状态都生效
            [['status'], 'string'],
            [['username'], 'unique'],
            [['email'], 'email',],
            [['password_hash',],'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => '用户名',
            'auth_key' => 'Auth Key',
            'password_hash' => '密码',
            'password_reset_token' => 'Password Reset Token',
            'email' => '邮箱',
            'status' => '状态正常',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
            'last_login_time' => '最后登录时间',
            'last_login_ip' => '最后登录IP',
            'password'=>'密码',
        ];
    }

    /**
     * Finds an identity by the given ID.
     * @param string|int $id the ID to be looked for
     * @return IdentityInterface the identity object that matches the given ID.
     * Null should be returned if such an identity cannot be found
     * or the identity is not in an active state (disabled, deleted, etc.)
     */
    public static function findIdentity($id)
    {
        return self::findOne(['id'=>$id]);
    }

    /**
     * Finds an identity by the given token.
     * @param mixed $token the token to be looked for
     * @param mixed $type the type of the token. The value of this parameter depends on the implementation.
     * For example, [[\yii\filters\auth\HttpBearerAuth]] will set this parameter to be `yii\filters\auth\HttpBearerAuth`.
     * @return IdentityInterface the identity object that matches the given token.
     * Null should be returned if such an identity cannot be found
     * or the identity is not in an active state (disabled, deleted, etc.)
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        // TODO: Implement findIdentityByAccessToken() method.
        return self::findOne(['password_reset_token'=>$token]);
    }

    /**
     * Returns an ID that can uniquely identify a user identity.
     * @return string|int an ID that uniquely identifies a user identity.
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Returns a key that can be used to check the validity of a given identity ID.
     *
     * The key should be unique for each individual user, and should be persistent
     * so that it can be used to check the validity of the user identity.
     *
     * The space of such keys should be big enough to defeat potential identity attacks.
     *
     * This is required if [[User::enableAutoLogin]] is enabled.
     * @return string a key that is used to check the validity of a given identity ID.
     * @see validateAuthKey()
     */
    public function getAuthKey()
    {
        // TODO: Implement getAuthKey() method.
        return $this->auth_key;
    }

    /**
     * Validates the given auth key.
     *
     * This is required if [[User::enableAutoLogin]] is enabled.
     * @param string $authKey the given auth key
     * @return bool whether the given auth key is valid.
     * @see getAuthKey()
     */
    public function validateAuthKey($authKey)
    {
        // TODO: Implement validateAuthKey() method.
        return $this->auth_key==$authKey;
    }

    public static function Menus(){
        return  [

            ['label' => '管理','items'=>[
                ['label' => '主页','url'=>'site/index'],
                ['label' => '主页','url'=>'site/index'],
            ]],
        ];
    }
}
