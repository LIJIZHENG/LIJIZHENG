<?php
namespace backend\models;
use yii\db\ActiveRecord;
class User extends ActiveRecord{
    public function rules(){
        return [
           [['username','password_hash','email','status'],'required'],
//            [['created_at'],'integer']
        ];
    }
    public function attributeLabels(){
        return [
            'username' => '用户名',
            'password_hash'=>'哈希密码',
            'email'=>'邮箱',
            'status'=>'状态',
//            'created_at'=>'更新时间'
        ];
    }
}