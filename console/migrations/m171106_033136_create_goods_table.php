<?php

use yii\db\Migration;

/**
 * Handles the creation of table `goods`.
 */
class m171106_033136_create_goods_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('goods', [
            'id' => $this->primaryKey(),
            'name'=>$this->string(20),
            'sn'=>$this->string(20),
            'logo'=>$this->string(255),
            'goods_category_id'=>$this->integer(),
            'brand_id'=>$this->integer(),
            'market_price'=>$this->integer(),
            'shop_price'=>$this->integer(),
            'sort'=>$this->integer(),
            'create_time'=>$this->integer(),
            'view_times'=>$this->integer(),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('goods');
    }
}
