<?php

use yii\db\Migration;

/**
 * Handles the creation of table `article_category`.
 */
class m171103_052329_create_article_category_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('article_category', [
            'id' => $this->primaryKey(),
            'name'=>$this->string(),
            'intro'=>$this->text(),
            'sort'=>$this->smallInteger(),
            'status'=>$this->smallInteger(),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('article_category');
    }
}
