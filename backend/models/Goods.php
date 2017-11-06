<?php
namespace backend\models;
use yii\db\ActiveRecord;

class Goods extends ActiveRecord{
 public $imgFile;
 public function attributeLabels(){
     return [
         'name'=>'商品名称',
         'sn'=>'货号',
         'logo'=>'LOGO图片',
         'goods_category_id'=>'商品分类id',
         'brand_id'=>'品牌分类',
         'market_price'=>'市场价格',
         'shop_price'=>'商品价格',
         'stock'=>'库存',
         'is_on_sale'=>'是否在售(1在售 0下架)',
         'status'=>'状态(1正常 0回收站)',
         'sort'=>'排序',
         'view_times'=>'浏览次数',
     ];
 }
 public function rules(){
     [
         [['name','sn','logo','goods_category_id','brand_id','market_price','shop_price','stock','is_on_sale','status','sort','view_times']]
     ];
 }
}