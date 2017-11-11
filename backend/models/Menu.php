<?php

namespace backend\models;

use Yii;
use yii\helpers\ArrayHelper;
use backend\models\GoodsCategoryQuery;
use creocoder\nestedsets\NestedSetsBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "menu".
 *
 * @property integer $id
 * @property string $name
 * @property string $url
 * @property integer $parent_id
 * @property integer $sort
 */
class Menu extends ActiveRecord {
    public function rules()
    {
        return [
            [['label','parent_id','status'], 'required'],
            [['parent_id','url'], 'integer'],
            [['sort'], 'string'],
            [['label'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'=>'ID',
            'label' => '菜单名称',
            'parent_id' => '上级分类',
            'url'=>'地址/路由',
            'sort' => '排序',
            'status'=>'状态',
        ];
    }
    public static function getMenu(){
        return ArrayHelper::map(self::find()->asArray()->all(),'id','name');
    }
    public static function getUrl(){
    $auth = \Yii::$app->authManager;
    $permissions = $auth->getPermissions();
    return ArrayHelper::map($permissions,'name','name');
}
    //一级菜单和二级菜单的关系 1对多
    public function getChildren(){
        //儿子.parend_id---->父亲.id
        return $this->hasMany(self::className(),
            ['parent_id'=>'id']);
    }

}