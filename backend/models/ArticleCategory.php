<?php
namespace backend\models;
use yii\db\ActiveRecord;
class ArticleCategory extends ActiveRecord{
    public $imgFile;
    public function attributeLabels(){
        return [
            'name'=>'名称',
            'intro'=>'简介',
            'sort'=>'排序',
            'status'=>'状态',
        ];
    }
    public function rules(){
        return [
            [['name','intro'],'required'],
            ['status','required'],
            [['sort'],'integer'],
            ['imgFile','file','extensions'=>['jpg','jpeg','png','gif'],'skipOnEmpty'=>false],
        ];
    }
}