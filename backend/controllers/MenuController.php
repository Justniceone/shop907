<?php

namespace backend\controllers;

use backend\filters\RbacFilter;
use backend\models\Menu;
use backend\models\RolesForm;
use yii\data\Pagination;

class MenuController extends \yii\web\Controller
{
    public function actionIndex()
    {
        //获取menu所有数据

       $menus=Menu::find()->orderBy('id asc')->all();

        return $this->render('index',['menus'=>$menus]);
    }

    public function actionAdd(){

        $model=new Menu();
        $model->sort=0;
        $request=\Yii::$app->request;

        if($request->isPost){
            $model->load($request->post());
            if($model->validate()){
                $model->save();
                return $this->redirect(['menu/index']);
            }
        }
        return $this->render('add',['model'=>$model]);
    }

    public function actionEdit($id){
        $request=\Yii::$app->request;
        $model=Menu::findOne(['id'=>$id]);

        if($request->isPost){
            $model->load($request->post());
            if($model->validate()){
                $model->save();
                return $this->redirect(['menu/index']);
            }
        }
        return $this->render('add',['model'=>$model]);
    }

    public function actionDelete(){
        //接收ajax请求
        $id=\Yii::$app->request->get('id');
        //根据id删除数据
        Menu::findOne(['id'=>$id])->delete();
        return json_encode([
            'success'=>true,
            'msg'=>'删除成功',
        ]);

    }

    //配置rbac权限
    public function behaviors()
    {

        return [
            'rbac'=>[
                'class'=>RbacFilter::className(),
                'except'=>['login','logout','captcha','error','change','s-upload'],
            ]
        ];
    }
}
