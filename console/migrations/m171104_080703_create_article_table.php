<?php

use yii\db\Migration;

/**
 * Handles the creation of table `article`.
 */
class m171104_080703_create_article_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('article', [
            'id' => $this->primaryKey(),
            'name'=>$this->string(),
            'intro'=>$this->text(),
            'article_category_id'=>$this->smallInteger(),
            'sort'=>$this->integer(11),
            'status'=>$this->smallInteger(),
            'create_time'=>$this->smallInteger(),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('article');
    }
}
