<?php
namespace backend\controllers;
use yii\db\ActiveRecord;

class Goods_intro extends ActiveRecord{
    public $imgFile;
    public static function getStatusOptions($hidden_del=true){
        $options =  [
            -1=>'删除', 0=>'隐藏', 1=>'正常'
        ];
        if($hidden_del){
            unset($options['-1']);
        }
        return $options;
    }
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'brand';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['goods_id','path'],'required']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'goods_id'=>'商品id',
            'path'=>'图片地址',
            ];
    }
}