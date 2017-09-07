<?php

namespace backend\controllers;

use backend\models\Brand;
use yii\data\Pagination;
use yii\web\Request;
use yii\web\UploadedFile;

class BrandController extends \yii\web\Controller
{
    public function actionIndex()

    {
        //分页列表
        $pager=new Pagination([
            'pageSize'=>3,
            'totalCount'=>Brand::find()->count(),
        ]);

        $models=Brand::find()->where(['>','status',-1])->orderBy('sort desc')->offset($pager->offset)->limit($pager->limit)->all();
        return $this->render('index',['pager'=>$pager,'models'=>$models]);
    }

    public function actionAdd(){
        //添加功能
        $model=new Brand();
        //保存提交数据
        $request=new Request();
        if($request->isPost){
            //载入表单数据
            $model->load($request->post());
            //将图片保存到属性上
            $model->file=UploadedFile::getInstance($model,'file');
            //验证表单数据
            if($model->validate()){
                if($model->file){

                    $path='/assets/upload/'.time().'.'.$model->file->getExtension();
                    //保存图片
                    $model->file->saveAs(\Yii::getAlias('@webroot').$path,false);
                    //保存logo字段
                    $model->logo=$path;
                }

                $model->save(false);
                \Yii::$app->session->setFlash('success','操作成功');
                return $this->redirect(['brand/index']);
            }
        }
        return $this->render('add',['model'=>$model]);

    }

    public function actionEdit(){
        //修改功能
        $request=new Request();
        $id=$request->get('id');
        $model=Brand::findOne(['id'=>$id]);
        if($request->isPost){
            //载入表单数据
            $model->load($request->post());
            //将图片保存到属性上
            $model->file=UploadedFile::getInstance($model,'file');
            //验证表单数据
            if($model->validate()){

                if($model->file){
                    $path='/assets/upload/'.time().'.'.$model->file->getExtension();
                    //保存图片
                    $model->file->saveAs(\Yii::getAlias('@webroot').$path,false);
                    //保存logo字段
                    $model->logo=$path;
                }

                $model->save(false);
                \Yii::$app->session->setFlash('success','操作成功');
                return $this->redirect(['brand/index']);
            }

        }
        return $this->render('add',['model'=>$model]);
    }

    public function actionDelete($id){
        $model=Brand::findOne(['id'=>$id]);
        //改变status状态为-1
        $model->status=-1;
        $result=$model->save(false);
        if($result){
            \Yii::$app->session->setFlash('success','删除成功');
            return $this->redirect(['brand/index']);
        }
    }

    //Ajax不刷新删除
    public function actionAjax(){

    }
}
