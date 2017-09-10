<?php
namespace backend\controllers;
use backend\models\ArticleDetail;
use yii\web\Controller;

class ArticleDetailController extends Controller{
    public function actionIndex(){
        $article_id=\Yii::$app->request->get('id');
        $model=ArticleDetail::findOne(['article_id'=>$article_id]);
        return $this->render('index',['model'=>$model]);
    }
}