<?php

namespace frontend\controllers;

use backend\models\Goods;
use backend\models\GoodsCategory;
use frontend\models\Address;
use frontend\models\Member;
use frontend\models\SmsDemo;

class MemberController extends \yii\web\Controller
{
    public $enableCsrfValidation=false;

    public function actionRegist(){

        //用户注册功能
        $request=\Yii::$app->request;
        if($request->isPost){
            $model=new Member();
            $model->load($request->post(),'');
            if($model->validate()){
                //将明文密码加密 增加添加时间 添加状态为正常 添加更新时间
                $model->password_hash=\Yii::$app->security->generatePasswordHash($model->password);
                $model->created_at=time();
                $model->updated_at=time();
                $model->status=1;
                $model->save();
                \Yii::$app->session->setFlash('success','注册成功');
            }
            return $this->redirect(['login']);
        }
        return $this->renderPartial('regist');
    }

    public function actionLogin(){

        //用户登录功能
        //接收表单数据
        $model=new Member();
        $request=\Yii::$app->request;
        if($request->isPost){
            $model->load($request->post(),'');
            if($model->validate()){
                //验证数据
                //判断用户名是否存在
                $name=Member::findOne(['username'=>$model->username]);
                if($name){
                    //用户名存在继续判断密码是否正确
                    if(\Yii::$app->security->validatePassword($model->password,$name->password_hash)){
                        //密码正确,保存登录ip,时间和更新时间
                        $name->last_login_time=time();
                        $name->last_login_ip=$request->userIP;
                        $name->updated_at=time();
                        //保存登录标记,判断用户是否自动登录
                        $duration=$model->remember?86400:0;
                        //自动登录生成随机字符串
                        if($duration){
                            $name->auth_key=\Yii::$app->security->generateRandomString();
                        }
                        //保存
                        $name->save(false);

                        \Yii::$app->user->login($name,$duration);
                        \Yii::$app->session->setFlash('success','登录成功');
                        //同步cookie中的商品信息
                        GoodsController::actionSys();

                        return $this->redirect(['goods/index']);
                    }else{
                        $name->addError('password','密码错误');
                    }

                }else{
                    $model->addError('username','用户不存在');
                }
            }
            return $this->redirect(['login']);
        }
        return $this->renderPartial('login');
    }

    public function actionUser(){

        return $this->renderPartial('user');
    }

    public function actionUnique($username){
        //验证用户名唯一性
        //根据用户名查询是否已经注册
        $user=Member::findOne(['username'=>$username]);
        if($user){
            return 'false';
        }else{
            return 'true';
        }
    }

    public function actionLogout(){
        //用户注销
        \Yii::$app->user->logout();
        \Yii::$app->session->setFlash('success','注销成功');
        return $this->redirect(['goods/index']);
    }

    public function actionAddress(){

        //收货地址管理
        $model=new Address();
        $request=\Yii::$app->request;
        if($request->isPost){

            $model->load($request->post(),'');
            if($model->validate()){
                //获取地址详情
                $model->province=$model->cmbProvince;
                $model->city=$model->cmbCity;
                $model->area=$model->cmbArea;
               //获取登录用户id
                $model->member_id=\Yii::$app->user->id;
                $model->save();
                return $this->redirect(['member/address']);
            }
        }

        return $this->renderPartial('address');
    }

    public function actionIndex(){
        //商城首页
        // var_dump(\Yii::$app->user->identity);
        //获取所有商品数据,以及商品分类数据
        $categorys=GoodsCategory::find()->where(['depth'=>0])->all();
        $seconds=GoodsCategory::find()->where(['depth'=>1])->all();
        $goods=Goods::find()->all();
        $third=GoodsCategory::find()->where(['depth'=>2])->all();

        return $this->renderPartial('index',['goods'=>$goods,'categorys'=>$categorys,'seconds'=>$seconds,'third'=>$third]);

    }

    public function actionChangeAddress(){
        //修改收货地址
        $request=\Yii::$app->request;
        $id=$request->get('id');
        $model=Address::findOne(['id'=>$id]);

        //接收表单数据修改
        if($request->isPost){
           // var_dump($model,$request->post());die;
            $model->load($request->post(),'');
            if($model->validate()){

                $model->province=$model->cmbProvince;
                $model->city=$model->cmbCity;
                $model->area=$model->cmbArea;
                $model->save();
                return $this->redirect(['member/address']);
            }else{
                var_dump($model->getErrors());
            }
        }
        //回显数据
        return $this->renderPartial('edit-address',['model'=>$model]);
    }

    public function actionDeleteAddress($id){
        //删除地址
        Address::findOne(['id'=>$id])->delete();
        return $this->redirect(['member/address']);
    }



    public function actionSms(){
        //发送短信功能
        $code=mt_rand(10000,99999);
        //获取ajax传递过来的手机号
        $cellphone=\Yii::$app->request->post('tel');
        //保存用户手机号和短信验证码到session中
        \Yii::$app->session->set('message_'.$cellphone,$code);

        $demo = new SmsDemo(
            "LTAIaGKxVSdRcAH6",
            "vOcZwQ76qPPLaXHvo0zD9RdHImpu6z"
        );

        echo "SmsDemo::sendSms\n";
        $response = $demo->sendSms(
            "波胖商城欢迎你", // 短信签名
            "SMS_97960025", // 短信模板编号
            "$cellphone", // 短信接收者
            Array(  // 短信模板中字段的值
                "code"=>$code
            ),
            "123"
        );
        print_r($response);

        //保存验证码到session中
        echo $code;
       // echo $cellphone;
        //15882964203
    }

    public function actionMessage($checkcode,$tel){
        //接收ajax数据验证短信验证码是否正确
        $code=\Yii::$app->session->get('message_'.$tel);

        //判断是否一致
        if($code==null || $code !=$checkcode){
            return 'false';
        }
        return 'true';
    }
}
