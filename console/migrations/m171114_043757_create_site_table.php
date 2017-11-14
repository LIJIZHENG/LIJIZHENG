<?php

use yii\db\Migration;

/**
 * Handles the creation of table `site`.
 */
class m171114_043757_create_site_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('site','cmbProvince',$this->string());
        $this->addColumn('site','cmbCity',$this->string());
        $this->addColumn('site','cmbArea',$this->string());
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('site');
    }
}
