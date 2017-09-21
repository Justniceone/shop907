<?php

namespace backend\controllers;

use backend\filters\RbacFilter;
use backend\models\Article;
use backend\models\ArticleCategory;
use backend\models\ArticleDetail;
use yii\data\Pagination;
use yii\helpers\ArrayHelper;
use yii\web\Request;

class ArticleController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $pager=new Pagination([
            'totalCount'=>Article::find()->where(['>','status',-1])->count(),
            'pageSize'=>3,
        ]);
        $models=Article::find()->where(['>','status',-1])->orderBy('sort desc')->offset($pager->offset)->limit($pager->limit)->all();
        return $this->render('index',['pager'=>$pager,'models'=>$models]);
    }
    public function actionAdd(){
        //获取分类的种类
        $type=ArticleCategory::find()->asArray()->all();
       // $array=ArrayHelper::map($type,'article_category_id','name');
        $array=[];
        foreach ($type as $v){
            $array[$v['id']]=$v['name'];
        }
        //print_r($array);
        $model=new Article();
        $content=new ArticleDetail();
        $request=new Request();
        if($request->isPost){
            //载入表单数据
            $model->load($request->post());
            $content->load($request->post());
            //验证表单数据
            if($model->validate() && $content->validate()){
                $model->create_time=time();
                $model->save();
                $content->article_id=$model->id;
                if( $content->save()){
                    \Yii::$app->session->setFlash('success','操作成功');
                    return $this->redirect(['article/index']);
                }
            }
        }
         return $this->render('add',['model'=>$model,'array'=>$array,'content'=>$content]);
    }

    public function actionDelete(){
        //根据id查找一条记录
        $request=\Yii::$app->request;
        $id=$request->get('id');
        $model=Article::findOne(['id'=>$id]);
        $model->status=-1;
        $model->save(false);
        return $this->redirect(['article/index']);
    }


    public function actionEdit(){
        //获取分类的种类
        $type=ArticleCategory::find()->asArray()->all();
        // $array=ArrayHelper::map($type,'article_category_id','name');
        $array=[];
        foreach ($type as $v){
            $array[$v['id']]=$v['name'];
        }

        //根据id查找一条记录
        $request=\Yii::$app->request;
        $id=$request->get('id');
        $model=Article::findOne(['id'=>$id]);
        $content=ArticleDetail::findOne(['article_id'=>$id]);

        if($request->isPost){
            //载入表单数据
            $model->load($request->post());
            $content->load($request->post());
            //验证表单数据
            if($model->validate() && $content->validate()){

                $model->save();
               // var_dump($model);die;

              //  $content->save();

                if( $content->save()){
                    \Yii::$app->session->setFlash('success','操作成功');
                    return $this->redirect(['article/index']);
                }
            }
        }

                return $this->render('edit',['model'=>$model,'content'=>$content,'array'=>$array]);
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
