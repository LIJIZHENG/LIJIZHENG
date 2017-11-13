<?php

namespace backend\models;

use Yii;
use yii\helpers\ArrayHelper;
use backend\models\GoodsCategoryQuery;
use creocoder\nestedsets\NestedSetsBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "Menu".
 *
 * @property integer $id
 * @property integer $tree
 * @property integer $lft
 * @property integer $rgt
 * @property integer $depth
 * @property string $name
 * @property integer $parent_id
 * @property string $intro
 */
class Menu extends ActiveRecord {
    public function rules()
    {
        return [
            [['label', 'parent_id','status','url'], 'required'],
            [['parent_id'], 'integer'],
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
            'label' => '菜单名称',
            'parent_id' => '上级分类',
            'sort' => '排序',
            'status'=>'状态',
            'url'=>'路由/地址',
        ];
    }
    public static function getMenu(){
        return ArrayHelper::map(self::find()->asArray()->all(),'id','label');
    }
    public static function getUrl(){
        $auth = \Yii::$app->authManager;
        $permissions = $auth->getPermissions();
        return ArrayHelper::map($permissions,'name','name');

    }
    //一级菜单和二级菜单的关系 1对多
    //一级菜单和二级菜单的关系  1对多
    public function getChildren(){
        //儿子.parent_id  --->  父亲.id
        return $this->hasMany(self::className(),['parent_id'=>'id']);
    }
}
