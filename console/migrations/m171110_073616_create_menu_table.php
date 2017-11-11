<?php

use yii\db\Migration;

/**
 * Handles the creation of table `menu`.
 */
class m171110_073616_create_menu_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('menu','tree',$this->string());

        $this->addColumn('menu','lft',$this->string());
        $this->addColumn('menu','rgt',$this->string());
        $this->addColumn('menu','depth',$this->string());

    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('menu');
    }
}
