<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "menu".
 *
 * @property string $id
 * @property string $name
 * @property integer $parent_id
 * @property string $url
 * @property integer $sort
 */
class Menu extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'menu';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['parent_id', 'sort'], 'string'],
            [['name'], 'string'],
            [['url'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '名称',
            'parent_id' => '上级菜单',
            'url' => '地址/路由',
            'sort' => '排序',
        ];
    }

    public static function menu(){
       $menus= Menu::find()->where(['parent_id'=>0])->asArray()->all();
       $all=[];
       foreach ($menus as $menu){
           $all[$menu['id']]=$menu['name'];
       }
       return $all;
    }

    public static function Menus(){

        //获取所有一级菜单
        $menuItems=[];
        $menus=Menu::find()->where(['parent_id'=>0])->all();
        foreach ($menus as $menu){
           $second=Menu::find()->where(['parent_id'=>$menu->id])->all();
           $items=[];
           foreach ($second as $value){
               //判断用户是否有看到菜单的权限
               if(Yii::$app->user->can($value->url)){
                   $items[]=['label'=>$value->name,'url'=>[$value->url]];
               }
           }

  /*          $items=[
                ['label' => '主页','url'=>'site/index'],
                ['label' => '主页','url'=>'site/index'],
            ];*/
            $menuItems[]=['label' => $menu->name,'items'=>$items];
        }
        return $menuItems;
/*        $menuItems=  [
            ['label' => '管理','items'=>[
                ['label' => '主页','url'=>'site/index'],
                ['label' => '主页','url'=>'site/index'],
            ]],
        ];*/

    }
}
