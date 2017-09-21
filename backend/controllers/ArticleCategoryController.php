<?php

namespace backend\controllers;

use backend\filters\RbacFilter;
use backend\models\ArticleCategory;
use yii\data\Pagination;
use yii\filters\AccessControl;
use yii\web\Request;

class ArticleCategoryController extends \yii\web\Controller
{
    public function actionIndex()
    {
        //列表功能
        $pager=new Pagination([
            'totalCount'=>ArticleCategory::find()->where(['>','status',-1])->count(),
            'pageSize'=>3,
        ]);
        $models=ArticleCategory::find()->where(['>','status',-1])->orderBy('sort desc')->offset($pager->offset)->limit($pager->limit)->all();
        return $this->render('index',['models'=>$models,'pager'=>$pager]);
    }

    public function actionAdd(){
        //添加功能
        $model=new ArticleCategory();
        //保存提交数据
        $request=new Request();
        if($request->isPost){
            //载入表单数据
            $model->load($request->post());

            //验证表单数据
            if($model->validate()){
                $model->save();
                \Yii::$app->session->setFlash('success','操作成功');
                return $this->redirect(['article-category/index']);
            }
        }
        return $this->render('add',['model'=>$model]);

    }

    public function actionEdit(){
        $request=\Yii::$app->request;
        $id=$request->get('id');
        $model=ArticleCategory::findOne(['id'=>$id]);
        if($request->isPost){
            //载入表单数据
            $model->load($request->post());

            //验证表单数据
            if($model->validate()){
                $model->save();
                \Yii::$app->session->setFlash('success','操作成功');
                return $this->redirect(['article-category/index']);
            }
        }
        return $this->render('add',['model'=>$model]);
    }
    public function actionDelete($id){
        $model=ArticleCategory::findOne(['id'=>$id]);
        //修改状态
        $model->status=-1;
        $result=$model->save(false);
        if($result){
            return $this->redirect(['article-category/index']);
        }else{
            var_dump($model->getErrors());
        }
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
