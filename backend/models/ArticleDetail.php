<?php
namespace backend\models;
use yii\db\ActiveRecord;

class ArticleDetail extends ActiveRecord{

    public function attributeLabels(){
        return [
          'article_id'=>'文章id',
          'content'=>'简介'
        ];
    }
    public function rules(){
        return [
            [['article_id','content'],'required'],
        ];
    }

}