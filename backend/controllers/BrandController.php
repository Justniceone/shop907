<?php

namespace backend\controllers;

use backend\filters\RbacFilter;
use backend\models\Brand;
use yii\data\Pagination;
use yii\web\Request;
use yii\web\UploadedFile;
use flyok666\uploadifive\UploadAction;
use flyok666\qiniu\Qiniu;
class BrandController extends \yii\web\Controller
{
    public $enableCsrfValidation=false;
    public function actionIndex()

    {
        //分页列表
        $pager=new Pagination([
            'totalCount'=>Brand::find()->where(['>','status',-1])->count(),
            'pageSize'=>3,
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
//            $model->file=UploadedFile::getInstance($model,'file');
            //验证表单数据
            if($model->validate()){
/*                if($model->file){

                    $path='/assets/upload/'.time().'.'.$model->file->getExtension();
                    //保存图片
                    $model->file->saveAs(\Yii::getAlias('@webroot').$path,false);
                    //保存logo字段
                    $model->logo=$path;
                }*/

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
//            $model->file=UploadedFile::getInstance($model,'file');
            //验证表单数据
            if($model->validate()){
/*
                if($model->file){
                    $path='/assets/upload/'.time().'.'.$model->file->getExtension();
                    //保存图片
                    $model->file->saveAs(\Yii::getAlias('@webroot').$path,false);
                    //保存logo字段
                    $model->logo=$path;
                }
*/
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

    public function actionAjaxdel(){
        //获取一条记录
        $id=\Yii::$app->request->get('id');
        $model=Brand::findOne(['id'=>$id]);
        $model->status=-1;
        if($model->save(false)){
            return json_encode([
                'msg'=>'删除成功',
                'success'=>true,
            ]);
        }else{
            return json_encode([
                'msg'=>'删除失败',
                'success'=>false,
            ]);
        }

    }


    public function actions() {
        return [
            's-upload' => [
                'class' => UploadAction::className(),
                'basePath' => '@webroot/assets/upload',
                'baseUrl' => '@web/assets/upload',
                'enableCsrf' => true, // default
                'postFieldName' => 'Filedata', // default
                //BEGIN METHOD
//               'format' => [$this, 'methodName'],
                //END METHOD
                //BEGIN CLOSURE BY-HASH
                'overwriteIfExist' => true,
/*
                'format' => function (UploadAction $action) {
                    $fileext = $action->uploadfile->getExtension();
                    $filename = sha1_file($action->uploadfile->tempName);
                    return "{$filename}.{$fileext}";
                },
 */
                //END CLOSURE BY-HASH
                //BEGIN CLOSURE BY TIME
                'format' => function (UploadAction $action) {
                    $fileext = $action->uploadfile->getExtension();
                    $filehash = sha1(uniqid() . time());
                    $p1 = substr($filehash, 0, 2);
                    $p2 = substr($filehash, 2, 2);
                    return "{$p1}/{$p2}/{$filehash}.{$fileext}";
                },
                //END CLOSURE BY TIME
                'validateOptions' => [
                    'extensions' => ['jpg', 'png','gif','bmp'],
                    'maxSize' => 1 * 1024 * 1024, //file size
                ],
                'beforeValidate' => function (UploadAction $action) {
                    //throw new Exception('test error');
                },
                'afterValidate' => function (UploadAction $action) {},
                'beforeSave' => function (UploadAction $action) {},
                'afterSave' => function (UploadAction $action) {
                 //   $action->output['fileUrl'] = $action->getWebUrl();
                    $action->getFilename(); // "image/yyyymmddtimerand.jpg"
                    $action->getWebUrl(); //  "baseUrl + filename, /upload/image/yyyymmddtimerand.jpg"
                    $action->getSavePath(); // "/var/www/htdocs/upload/image/yyyymmddtimerand.jpg"
                    //将图片保存到七牛云
/*                    $config = [
                        'accessKey'=>'gEaQS_5EWRYAAuz7nZc9plt40jRRb6HU7MI0aXwh',
                        'secretKey'=>'ABWGH-ma6kW55zcfwgzPxQJ9KUe_PQDXBn3ImW6q',
                        'domain'=>'http://ovy9vleun.bkt.clouddn.com',
                        'bucket'=>'bopang',
                        'area'=>Qiniu::AREA_HUADONG,
                    ];*/

                    $qiniu = new Qiniu(\Yii::$app->params['qiniuyun']);
                    $key = $action->getWebUrl();
                    //$file=\Yii::getAlias('@webroot/assets/upload/1.jpg');
                    $file=$action->getSavePath();
                    $qiniu->uploadFile($file,$key);
                    $url = $qiniu->getLink($key);
                    $action->output['fileUrl']=$url;//输出图片的路径

                },
            ],
        ];
    }


    //测试七牛云存储
    public function actionQiniu(){
    //use flyok666\qiniu\Qiniu;
        $config = [
            'accessKey'=>'gEaQS_5EWRYAAuz7nZc9plt40jRRb6HU7MI0aXwh',
            'secretKey'=>'ABWGH-ma6kW55zcfwgzPxQJ9KUe_PQDXBn3ImW6q',
            'domain'=>'http://ovy9vleun.bkt.clouddn.com',
            'bucket'=>'bopang520',
            'area'=>Qiniu::AREA_HUADONG
        ];

        $qiniu = new Qiniu($config);
        $key = time();
        //key通常使用文件名,通过key得到图片
        //$_FILES['tmp_name']表示要上传到七牛云的本地图片路径
        $qiniu->uploadFile($_FILES['tmp_name'],$key);
        $url = $qiniu->getLink($key);

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
