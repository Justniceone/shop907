<?php

namespace backend\controllers;

use backend\models\Admin;
use backend\models\AdminForm;
use yii\captcha\Captcha;
use yii\data\Pagination;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use yii\web\Request;
use yii\web\User;

class AdminController extends \yii\web\Controller
{

    public function actionIndex()
    {
        $pager=new Pagination([
            'totalCount'=>Admin::find()->count(),
            'pageSize'=>5,
        ]);
        $models=Admin::find()->all();
        return $this->render('index',['models'=>$models,'pager'=>$pager]);
    }

    public function actionAdd(){
        $model=new Admin();
        //设置场景为添加
        $model->scenario='add';
        //接收表单提交数据
        $request=new Request();
        if($request->isPost){
            //加载
            $model->load($request->post());
            if($model->validate()){
                //保存
                $model->save();
                \Yii::$app->session->setFlash('success','添加成功');
                return $this->redirect('index');
            }
        }
        return $this->render('add',['model'=>$model]);
    }

    public function actionEdit(){
        $request=\Yii::$app->request;
        //查找一条记录
        $model=Admin::findOne(['id'=>$request->get('id')]);
        if($model == null){
            throw new NotFoundHttpException('非法请求');
        }

        //接收表单数据
        if($request->isPost){
            $model->load($request->post());
            $model->save(false);
            return $this->redirect('index');
        }
        return $this->render('add',['model'=>$model]);
    }

    public function actionDelete(){
        $request=\Yii::$app->request;
        //查找一条记录
        $model=Admin::findOne(['id'=>$request->get('id')]);
        //删除
        $model->delete();
        return $this->redirect('index');
    }

    public function actionLogin(){
        //登录功能
        $formmodel=new AdminForm();
        //接收登录数据
        $request=\Yii::$app->request;
        if($request->isPost){
            $formmodel->load($request->post());
            //查找用户名
            $username=$formmodel->username;
            $model=Admin::findOne(['username'=>$username]);

            if($model){
                //用户名存在,继续验证密码
                $result= \Yii::$app->security->validatePassword($formmodel->password_hash,$model->password_hash);
                if($result){
                    //验证通过
                    //记录登录时间,登录ip
                    $model->last_login_ip=$request->userIP;
                    $model->last_login_time=time();
                    $model->save(false);
                    \Yii::$app->session->setFlash('登录成功');

                    //判断是否勾选记住密码
                    $duration=$formmodel->remember?86400:0;
                    \Yii::$app->user->login($model,$duration);
                    return $this->redirect(['admin/index']);
                }else{
                    $formmodel->addError('password_hash','密码错误');
                }

            }else{
                $formmodel->addError('username','没有该用户');
            }
        }
        return $this->render('login',['formmodel'=>$formmodel]);
    }

    public function actionLogout(){
        //退出登录
        \Yii::$app->user->logout();
        \Yii::$app->session->setFlash('success','退出登录成功');
        return $this->redirect(['admin/index']);
    }

    public function actionChange(){
        //修改自己密码

        return $this->render('change');
    }

    public function behaviors()
    {
        return [
            'access'=>[
                'class'=>AccessControl::className(),
                'only'=>[],
                'rules'=>[
                    [
                        'allow'=>true,
                        'actions'=>['login','index'],
                        'roles'=>['?'],
                    ],
                    [
                        'allow'=>true,
                        'actions'=>['logout','add','edit','delete','index'],
                        'roles'=>['@'],
                    ],
                ],
            ]
        ];
    }
}
