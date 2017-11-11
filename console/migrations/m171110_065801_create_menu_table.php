<?php

use yii\db\Migration;

/**
 * Handles the creation of table `menu`.
 */
class m171110_065801_create_menu_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
//        $this->addColumn('menu','parent_id',$this->string());
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('menu');
    }
}
