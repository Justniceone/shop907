<?php

use yii\db\Migration;

/**
 * Handles the creation of table `article`.
 */
class m170907_100308_create_article_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('article', [
            'id' => $this->primaryKey(),

        ]);
    }
//        intro	text	简介
//        article_category_id	int()	文章分类id
//        sort	int(11)	排序
//        status	int(2)	状态(-1删除 0隐藏 1正常)
//        create_time	int(11)	创建时间
//

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('article');
    }
}
