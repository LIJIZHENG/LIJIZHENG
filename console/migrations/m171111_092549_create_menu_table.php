<?php

use yii\db\Migration;

/**
 * Handles the creation of table `menu`.
 */
class m171111_092549_create_menu_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('menu','url',$this->string());
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('menu');
    }
}
