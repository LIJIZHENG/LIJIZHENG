<?php
namespace backend\models;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

class Goods_intro extends ActiveRecord{
    public function rules()
    {
        return [
            [['goods_id','content'],'required']
        ];
    }
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'goods_id'=>'商品id',
            'content'=>'描述',
            ];
    }
public static function getChildren($id){
        $children=self::find()->where(["id"=>$id])->all();
        return $children;
}
}