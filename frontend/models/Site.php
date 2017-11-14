<?php
namespace frontend\models;
use yii\db\ActiveRecord;
class site extends ActiveRecord{
    public function rules()
    {
        return [
            [['name','cmbProvince','cmbCity','cmbArea','tel','address'],'required']
        ];
    }
    public function attributeLabels()
    {
        return [
          'name'=>'收货人',
          'cmbProvince'=>'省区',
          'cmbCity'=>'市区',
          'cmbArea'=>'城区',
          'tel'=>'手机号码',
          'address'=>'详细地址',
        ];
    }
}