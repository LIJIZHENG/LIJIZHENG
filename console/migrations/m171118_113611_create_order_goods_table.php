<?php

use yii\db\Migration;

/**
 * Handles the creation of table `order_goods`.
 */
class m171118_113611_create_order_goods_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('order_goods', [
            'id' => $this->primaryKey(),
            'order_id'=>$this->integer(),
            'goods_id'=>$this->integer(),
            'goods_name'=>$this->string(255),
            'logo'=>$this->string(255),
            'price'=>$this->decimal(),
            'amount'=>$this->integer(),
            'total'=>$this->decimal(),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('order_goods');
    }
}
