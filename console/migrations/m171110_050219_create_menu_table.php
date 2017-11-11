<?php

use yii\db\Migration;

/**
 * Handles the creation of table `menu`.
 */
class m171110_050219_create_menu_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('menu','status',$this->string());
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('menu');
    }
}
