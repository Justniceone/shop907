<?php

namespace backend\controllers;

use backend\models\Admin;
use backend\models\AdminForm;
use backend\models\RolesForm;
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

        //创建角色
        $roles_form=new RolesForm();
        $roles=[];
        foreach (RolesForm::roles() as $role){
            $roles[$role['name']]=$role['description'];
        }
        return $this->render('add',['model'=>$model,'roles_form'=>$roles_form,'roles'=>$roles]);
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
        $FormModel=new AdminForm();
        $indentity=\Yii::$app->user->identity;
        //没有登录为NULL
        if(!$indentity){
            throw new NotFoundHttpException('请先登录');
        }
        $id=$indentity->id;
        $request=\Yii::$app->request;
        //提交表单修改
        if($request->isPost){
            $FormModel->load($request->post());
            //判断旧密码是否正确

            $result=\Yii::$app->security->validatePassword($FormModel->password_hash,$indentity->password_hash);
            if($result){
                //判断两次输入密码是否一致
                if($FormModel->new_password != $FormModel->re_password){
                    $FormModel->addError('re_password','两次输入不一致');
                }else{
                    //查询出一条记录,并更新密码
                    //$model=Admin::findOne(['id'=>$id]);
                    $model=\Yii::$app->user->identity;
                    $model->password_hash=\Yii::$app->security->generatePasswordHash($FormModel->re_password);
                    //保存
                    $model->save(false);
                    \Yii::$app->user->logout();
                    \Yii::$app->session->setFlash('success','密码修改成功');
                    //注销登录
                    return $this->redirect('login');
                }

            }else{
                $FormModel->addError('password_hash','密码不正确');
            }

        }
        return $this->render('change',['FormModel'=>$FormModel,'id'=>$indentity]);
    }



/*    public function behaviors()
    {
        return [
            'access'=>[
                'class'=>AccessControl::className(),
                'only'=>[],
                'rules'=>[
                    [
                        'allow'=>true,
                        'actions'=>['login','index','change'],
                        'roles'=>['?'],
                    ],
                    [
                        'allow'=>true,
                        'actions'=>['logout','add','edit','delete','index',],
                        'roles'=>['@'],
                    ],
                ],
            ]
        ];
    }*/

}
