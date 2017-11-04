<?php

use yii\db\Migration;

/**
 * Handles the creation of table `article_detail`.
 */
class m171104_081436_create_article_detail_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('article_detail', [
            'id' => $this->primaryKey(),
            'article_id'=>$this->smallInteger(),
            'content'=>$this->text(),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('article_detail');
    }
}
