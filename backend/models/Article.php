<?php
namespace backend\models;
use yii\db\ActiveRecord;
class Article extends ActiveRecord{
    public $content;
 public function attributeLabels(){
     return [
         'name'=>'名称',
         'intro'=>'简介',
         'article_category_id'=>'文章分类id',
         'sort'=>'排序',
         'status'=>'状态',
         'content'=>'内容',
     ];
 }
 public function rules(){
     return [
       [['name','intro','status','article_category_id','content'],'required'],
         [['sort'],'integer'],
     ];
 }

}