<?php
namespace backend\models;
use yii\db\ActiveRecord;

class GoodsGallery extends ActiveRecord{
    public function rules(){
        return [
            [['path'],'required'],
        ];
}
    public function attributeLabels(){
        return [
            'id' => 'ID',
            'path' => '图片地址',
               ];
    }};