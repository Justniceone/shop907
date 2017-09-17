<?php

namespace backend\controllers;

use backend\models\Category;
use backend\models\GoodsCategory;
use yii\data\Pagination;
use yii\helpers\ArrayHelper;

class GoodsCategoryController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $pager=new Pagination([
            'pageSize'=>15,
            'totalCount'=>GoodsCategory::find()->count(),
        ]);
        $models=GoodsCategory::find()->offset($pager->offset)->limit($pager->limit)->all();
        return $this->render('index',['pager'=>$pager,'models'=>$models]);
    }

    public function actionAdd(){
        $model=new GoodsCategory();
        $request=\Yii::$app->request;
        $gts=GoodsCategory::find()->select(['id','parent_id','name'])->asArray()->all();
        //添加一条顶级分类
        $top=['id'=>0,'name'=>'顶级分类','parent_id'=>0];
        //合并到gts数组
        $topCategory=ArrayHelper::merge([$top],$gts);

        if($request->isPost){
            $model->load($request->post());
            if($model->validate()){
                //判断是否顶级节点
                if($model->parent_id){
                    $one=GoodsCategory::findOne(['id'=>$model->parent_id]);
                    $model->prependTo($one);
                }else{
                    //$model->parent_id=0;
                    $model->makeRoot();
                }
                return $this->redirect(['index']);
            }
        }
        return $this->render('add',['model'=>$model,'topCategory'=>json_encode($topCategory)]);
    }

    public function actionEdit(){
        $request=\Yii::$app->request;
        $gts=GoodsCategory::find()->select(['id','parent_id','name'])->asArray()->all();
        //添加一条顶级分类
        $top=['id'=>0,'name'=>'顶级分类','parent_id'=>0];
        //合并到gts数组
        $topCategory=ArrayHelper::merge([$top],$gts);
        $model=GoodsCategory::findOne(['id'=>$request->get('id')]);

        if($request->isPost){
            $model->load($request->post());
            if($model->validate()){
                //判断是否顶级节点
                if($model->parent_id){
                    $one=GoodsCategory::findOne(['id'=>$model->parent_id]);
                    $model->prependTo($one);
                }else{
                    //判断是否是顶级节点,是就保存,不添加
                    if($model->getOldAttribute('parent_id') == 0){
                        $model->save();
                    }else{
                        $model->makeRoot();
                    }
                }
                return $this->redirect(['index']);
            }
        }
        return $this->render('add',['model'=>$model,'topCategory'=>json_encode($topCategory)]);

    }

    public function actionDelete(){
        //分类下有子分类就不能删除
        $request=\Yii::$app->request;
        $id=$request->get('id');
        $model=GoodsCategory::findOne(['id'=>$id]);
        //$sons=GoodsCategory::find()->where(['parent_id'=>$id])->asArray()->all();

/*        if($sons){
            \Yii::$app->session->setFlash('danger','有子分类不能删除');
        }else{
            \Yii::$app->session->setFlash('success','删除成功');
            //删除
            $model->delete();
        }*/
        //判断是否是叶子节点
        if($model->isLeaf()){
            $model->deleteWithChildren();//删除当前节点及子节点
        }else{
            \Yii::$app->session->setFlash('danger','有子分类不能删除');
        }

        return $this->redirect('index');
    }


    public function actionTest(){
        $gts=GoodsCategory::find()->select(['id','parent_id','name'])->asArray()->all();

        return $this->renderPartial('test',['gts'=>$gts]);
    }
}
