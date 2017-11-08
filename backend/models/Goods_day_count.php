<?php
namespace backend\models;
class Goods_day_count extends ArticleCategory{
    public function rules(){
        return [
          [['day','count'],'required']
        ];
    }
    public function attributeLabels()
    {
        return [
            'day'=>'日期',
            'count'=>'商品数'
        ];
    }
}