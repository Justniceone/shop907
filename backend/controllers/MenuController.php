<?php

namespace backend\controllers;

use backend\models\Menu;
use backend\models\RolesForm;

class MenuController extends \yii\web\Controller
{
    public function actionIndex()
    {
        //获取menu所有数据
       $menus=Menu::find()->all();

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
        return "{'success':true,'msg':'删除成功'}";
    }
}
