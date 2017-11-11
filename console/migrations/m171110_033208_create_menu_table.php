<?php

use yii\db\Migration;

/**
 * Handles the creation of table `menu`.
 */
class m171110_033208_create_menu_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('menu','sort',$this->string());
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('menu');
    }
}
