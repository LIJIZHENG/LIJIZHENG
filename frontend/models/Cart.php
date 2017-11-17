<?php
namespace frontend\models;
use yii\db\ActiveRecord;

class Cart extends ActiveRecord{
    public static function tableName()
    {
        return 'cart';
    }
    public function rules()
    {
        return [
          [['goods_id','amount'],'integer'],
        ];
    }
    public function attributeLabels()
    {
        return [
            'id'=>'ID',
            'goods_id'=>'商品di',
            'amount'=>'数量',
            'member_id'=>'用户id',
        ];
    }
}