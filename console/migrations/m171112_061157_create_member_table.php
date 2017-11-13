<?php

use yii\db\Migration;

/**
 * Handles the creation of table `member`.
 */
class m171112_061157_create_member_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('member', [
            'id' => $this->primaryKey(),
            'username'=>$this->string(50),
            'auth_key'=>$this->string(32),
            'password_hash'=>$this->string(100),
            'email'=>$this->string(100),
            'tel'=>$this->char(11),
            'last_login_time'=>$this->integer(),
            'last_login_ip'=>$this->integer(),
            'status'=>$this->integer(1),
            'created_at'=>$this->integer(),
            'updated_at'=>$this->integer(),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('member');
    }
}
