<?php
namespace backend\models;
 use yii\base\Model;
 use yii\db\ActiveRecord;

 class Permissions extends Model {
     public $name;
     public $description;
     public function attributeLabels(){
         return [
           'name'=>'权限名',
           'description'=>'描述',
         ];
     }
     public function rules(){
         return [
             [['name','description'],'required'],

         ];
     }
 }