<?php

use yii\db\Migration;

/**
 * Handles the creation of table `site`.
 */
class m171114_022834_create_site_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('site', [
            'id' => $this->primaryKey(),
            'name'=>$this->string(),
            'site'=>$this->string(),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('site');
    }
}
