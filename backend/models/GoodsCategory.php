<?php

namespace backend\models;

use Yii;
use creocoder\nestedsets\NestedSetsBehavior;
use yii\helpers\Url;

/**
 * This is the model class for table "goods_category".
 *
 * @property integer $id
 * @property integer $tree
 * @property integer $lft
 * @property integer $rgt
 * @property integer $depth
 * @property string $name
 * @property integer $parent_id
 * @property string $intro
 */
class GoodsCategory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'goods_category';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'parent_id'], 'required'],
            [['tree', 'lft', 'rgt', 'depth', 'parent_id'], 'integer'],
            [['intro'], 'string'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'tree' => 'Tree',
            'lft' => 'Lft',
            'rgt' => 'Rgt',
            'depth' => 'Depth',
            'name' => '名称',
            'parent_id' => '上级分类',
            'intro' => '简介',
        ];
    }

    public function behaviors() {
        return [
            'tree' => [
                'class' => NestedSetsBehavior::className(),
                'treeAttribute' => 'tree',
                // 'leftAttribute' => 'lft',
                // 'rightAttribute' => 'rgt',
                // 'depthAttribute' => 'depth',
            ],
        ];
    }

    public function transactions()
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_ALL,
        ];
    }

    public static function find()
    {
        return new Category(get_called_class());
    }

    public static function GoodsCategory()
    {
        $tops=self::find()->where(['parent_id'=>0])->all();
        $redis=new \Redis();
        $redis->connect('127.0.0.1');
        $html = $redis->get('goods_categories');//redis中如果有直接从Redis中取
        if($html===false){
            $html='';
            foreach ($tops as $top):
                $html.='<div class="cat">';
                $html.='<h3><a href='.Url::to(["goods/list?cate_id=$top->id"]).'>'.$top->name.'</a><b></b></h3>';
                $html.='<div class="cat_detail none">';
                foreach ($top->children(1)->all() as $child):
                    $html.='<dl class="">';
                    $html.='<dt><a href='.Url::to(["goods/list?cate_id=$child->id"]).'>'.$child->name.'</a></dt>';
                    foreach ($child->children()->all() as $son):
                        $html.='<dd>';
                        $html.='<a href='.Url::to(["goods/list?cate_id=$son->id"]).'>'.$son->name.'</a>';
                        $html.='</dd>';
                    endforeach;
                    $html.='</dl>';
                endforeach;
                $html.='</div>';
                $html.='</div>';
            endforeach;
        }

        //将分类信息保存到Redis中

        $redis->set('goods_categories',$html);
        return $html;
    }
}
