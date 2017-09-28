<?php
namespace backend\controllers;

use yii\web\Controller;

class StaticController extends Controller{
    //生成静态化首页
    public function actionIndex(){
    $data=$this->renderPartial('@frontend/views/goods/index.php');
    var_dump($data);
    }
}