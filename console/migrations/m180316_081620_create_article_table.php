<?php

use yii\db\Migration;

/**
 * Handles the creation of table `article`.
 */
class m180316_081620_create_article_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        //创建文章表
        $this->createTable('article', [
            'id' => $this->primaryKey(),
            'title'=>$this->string()->notNull()->comment('标题'),
            'intro'=>$this->text()->comment('简介'),
            'sort'=>$this->smallInteger()->notNull()->defaultValue(100)->comment('排序'),
            'status'=>$this->smallInteger()->notNull()->defaultValue(1)->comment('状态'),
            'cate_id'=>$this->integer()->comment('分类Id'),
            'create_time'=>$this->integer()->comment('创建时间'),
            'update_time'=>$this->integer()->comment('更新时间'),
        ]);
        //创建文章详情表
        $this->createTable('article_content',[
            'id'=>$this->primaryKey(),
            'detail'=>$this->text()->comment('内容'),
            'article_id'=>$this->integer()->comment('文章Id')

        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('article');
        $this->dropTable('article_content');
    }
}
