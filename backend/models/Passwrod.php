<?php
namespace backend\models;
use yii\db\ActiveRecord;

class Passwrod extends ActiveRecord{
    public $oldPassword_hash;
    public $newPassword_hash;
    public $rePassword_hash;

    public function rules()
    {
        return [
            [['oldPassword_hash','newPassword_hash','rePassword_hash'],'required'],
            //新密码和确认新密码一致
            ['rePassword_hash','compare','compareAttribute'=>'newPassword_hash','message'=>'两次密码不一致'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'oldPassword_hash'=>'旧密码',
            'newPassword_hash'=>'新密码',
            'rePassword_hash'=>'确认新密码',
        ];
    }
}