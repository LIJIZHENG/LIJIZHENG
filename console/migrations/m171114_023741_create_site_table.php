<?php

use yii\db\Migration;

/**
 * Handles the creation of table `site`.
 */
class m171114_023741_create_site_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('site','tel',$this->string());
        $this->addColumn('site','address',$this->string());
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('site');
    }
}
