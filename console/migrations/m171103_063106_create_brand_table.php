<?php

use yii\db\Migration;

/**
 * Handles the creation of table `brand`.
 */
class m171103_063106_create_brand_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('brand', [
            'id' => $this->primaryKey(),
            'name'=>$this->string(),
            'intro'=>$this->text(),
            'logo'=>$this->string(),
            'sort'=>$this->smallInteger(),
            'status'=>$this->smallInteger(),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('brand');
    }
}
