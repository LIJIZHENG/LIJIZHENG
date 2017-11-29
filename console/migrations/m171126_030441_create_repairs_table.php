<?php

use yii\db\Migration;

/**
 * Handles the creation of table `repairs`.
 */
class m171126_030441_create_repairs_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('repairs', [
            'id' => $this->primaryKey(),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('repairs');
    }
}
