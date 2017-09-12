<?php

namespace backend\controllers;

use backend\models\Brand;
use backend\models\Goods;
use backend\models\GoodsCategory;
use backend\models\GoodsDayCount;
use backend\models\GoodsGallery;
use backend\models\GoodsIntro;
use flyok666\uploadifive\UploadAction;
use yii\data\Pagination;

class GoodsController extends \yii\web\Controller
{
    public $enableCsrfValidation=false;
    public function actionIndex()
    {
        $where= \Yii::$app->request->get();
       // print_r($where);die;

        //$conditions=implode(',',$where);
        //echo $name;die;
        $name=isset($where['name'])?$where['name']:'';
        $sn=isset($where['sn'])?$where['sn']:'';
        $sprice=isset($where['sprice'])?$where['sprice']:'';
        $tprice=isset($where['tprice'])?$where['tprice']:'';

        $pager=new Pagination([
            'totalCount'=> $models=Goods::find()->Where(['status'=>1])->andFilterWhere(['like','name',$name])->andFilterWhere(['like','sn',$sn])->andFilterWhere(['between','shop_price',$sprice,$tprice])->count(),
            'pageSize'=>4,
        ]);


        $models=Goods::find()->Where(['status'=>1])->andFilterWhere(['like','name',$name])->andFilterWhere(['like','sn',$sn])->andFilterWhere(['between','shop_price',$sprice,$tprice])->orderBy('sort desc')->offset($pager->offset)->limit($pager->limit)->all();
        return $this->render('index',['pager'=>$pager,'models'=>$models]);
    }

    public function actionAdd(){
        $gts=GoodsCategory::find()->select(['id','parent_id','name'])->asArray()->all();
        //获取所有品牌
        $all=Brand::find()->asArray()->all();
        $brands=[];
        foreach ($all as $v){
            $brands[$v['id']]=$v['name'];
        }
        $model=new Goods();
        $model->is_on_sale=1;
        $model->status=1;
        $model->sort=20;
        //内容模型
        $intro=new GoodsIntro();
        //接收表单数据
        $request=\Yii::$app->request;
        if($request->isPost ){
            $intro->load($request->post());
            $model->load($request->post());
         //   print_r($model->content);die;

            //验证数据
            if($model->validate() && $intro->validate()){
               // print_r($intro->content);die;
                //根据日期生成sn号,查询当日数量表
                $date=date('Ymd');
                $count=GoodsDayCount::findOne(['day'=>$date]);
               // print_r($count);die;
                if($count){
                    if($count->count <9){
                        //sn=date+000+($count->count+1)
                        $model->sn=$date.'000'.($count->count +1);

                    }elseif ($count->count<98){
                        //sn=date+00+($count->count+1)
                        $model->sn=$date.'00'.($count->count +1);
                    }

                    //将统计表count值加1
                    $count->count=$count->count+1;
                    $count->day=$date;
                    $count->save();

                }else{
                    $model->sn=$date.'0001';
                    //将count加1
                    $count=new GoodsDayCount();
                    $count->count=1;
                    $count->day=$date;
                    $count->save();
                }
                //保存商品信息
                $model->create_time=time();
                $model->save();

                //保存图片到相册
                $GoodsGallery=new GoodsGallery();
                $GoodsGallery->goods_id=$model->id;
                $GoodsGallery->path=$model->logo;
                $GoodsGallery->save(false);

                //保存详细信息到intro表
                $intro->goods_id=$model->id;
                $intro->save();

            }
            return $this->redirect('index');
        }
        return $this->render('add',['model'=>$model,'brands'=>$brands,'topCategory'=>json_encode($gts),'intro'=>$intro]);
    }


    public function actionEdit(){
        $id=\Yii::$app->request->get('id');
        $intro=GoodsIntro::findOne(['goods_id'=>$id]);
        //根据id查询数据
        //获取品牌
        $gts=GoodsCategory::find()->select(['id','parent_id','name'])->asArray()->all();
        //获取所有品牌
        $all=Brand::find()->asArray()->all();
        $brands=[];
        foreach ($all as $v){
            $brands[$v['id']]=$v['name'];
        }
        $model=Goods::findOne(['id'=>$id]);

        if(\Yii::$app->request->isPost){
            $intro->load(\Yii::$app->request->post());
            $model->load(\Yii::$app->request->post());
            // print_r($model);die;
            //验证数据
            if($model->validate() && $intro->validate()){
                $intro->save(false);
                $model->save(false);

                //保存图片到相册
                $GoodsGallery=new GoodsGallery();
                $GoodsGallery->goods_id=$model->id;
                $GoodsGallery->path=$model->logo;
                $GoodsGallery->save(false);
            }
            return $this->redirect('index');
        }
        return $this->render('add',['model'=>$model,'brands'=>$brands,'topCategory'=>json_encode($gts),'intro'=>$intro]);
    }

    public function actionDel($id){
        $model=Goods::findOne(['id'=>$id]);
        //修改商品状态为回收站
        $model->status=0;
        if($model->save(false)){
            \Yii::$app->session->setFlash('success','删除成功');
            return $this->redirect('index');
        }else{
            var_dump($model->getErrors());
        }

    }

    public function actionCheck(){
        $request=\Yii::$app->request;
        $id=$request->get('id');
        //查看相册
        $model=GoodsGallery::find()->where(['goods_id'=>$id])->asArray()->all();
       //接收goods_id保存

        $path=$request->get('fileUrl');
        if($path){
            $goods_id=$request->get('goods_id');
            $model=new GoodsGallery();
            $model->goods_id=$goods_id;
            $model->path=$path;
            $model->save(false);
            return json_encode(['success'=>true,'msg'=>'保存成功']);
        }
        //接收图片的URL
        return $this->render('check',['model'=>$model]);
    }

    public function actionAjax(){
        //接收数据,删除图片
        $request=\Yii::$app->request;
        //$goods_id=$request->get('goods_id');
        $path=$request->get('fileUrl');
        $result=GoodsGallery::findOne(['path'=>$path])->delete();
        var_dump($result);
        if($result){
            echo "{'success':true,'msg':'删除成功'}";
        }
    }

    //----------------------------------------------------
    public function actions() {
        return [
            's-upload' => [
                'class' => UploadAction::className(),
                'basePath' => '@webroot/upload',
                'baseUrl' => '@web/upload',
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
                    $action->output['fileUrl'] = $action->getWebUrl();
                    $action->getFilename(); // "image/yyyymmddtimerand.jpg"
                    $action->getWebUrl(); //  "baseUrl + filename, /upload/image/yyyymmddtimerand.jpg"
                    $action->getSavePath(); // "/var/www/htdocs/upload/image/yyyymmddtimerand.jpg"
                    //将图片保存到七牛云
                    /*
                    $qiniu = new Qiniu(\Yii::$app->params['qiniuyun']);
                    $key = $action->getWebUrl();
                    //$file=\Yii::getAlias('@webroot/assets/upload/1.jpg');
                    $file=$action->getSavePath();
                    $qiniu->uploadFile($file,$key);
                    $url = $qiniu->getLink($key);
                    $action->output['fileUrl']=$url;//输出图片的路径*/

                },
            ],

            //uedilt

                'upload' => [
                    'class' => 'kucha\ueditor\UEditorAction',
                    'config' => [
                        "imageUrlPrefix"  => "http://www.baidu.com",//图片访问路径前缀
                        "imagePathFormat" => "/upload/image/{yyyy}{mm}{dd}/{time}{rand:6}", //上传保存路径
                "imageRoot" => \Yii::getAlias("@webroot"),
            ],
        ]

        ];
    }

/*    public function actions()
    {
        return [
            'upload' => [
                'class' => 'kucha\ueditor\UEditorAction',
            ]
        ];
    }*/

}
