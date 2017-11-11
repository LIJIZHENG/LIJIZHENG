<?php

use yii\db\Migration;

/**
 * Handles the creation of table `user`.
 */
class m171108_025005_create_user_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {

        $this->addColumn('user','last_login_time',$this->integer());
            $this->addColumn('user','last_login_ip',$this->integer());
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('user');
    }
}
