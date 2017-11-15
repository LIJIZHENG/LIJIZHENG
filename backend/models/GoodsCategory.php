<?php

namespace backend\models;

use Yii;
use yii\helpers\ArrayHelper;
use backend\models\GoodsCategoryQuery;
use creocoder\nestedsets\NestedSetsBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "goods_category".
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
class GoodsCategory extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'goods_category';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'parent_id'], 'required'],
            [['tree', 'lft', 'rgt', 'depth', 'parent_id'], 'integer'],
            [['intro'], 'string'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'tree' => 'Tree',
            'lft' => 'Lft',
            'rgt' => 'Rgt',
            'depth' => 'Depth',
            'name' => '名称',
            'parent_id' => '上级分类',
            'intro' => '简介',
        ];
    }

    public function behaviors()
    {
        return [
            'tree' => [
                'class' => NestedSetsBehavior::className(),
                'treeAttribute' => 'tree',
                // 'leftAttribute' => 'lft',
                // 'rightAttribute' => 'rgt',
                // 'depthAttribute' => 'depth',
            ],
        ];
    }

    public function transactions()
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_ALL,
        ];
    }

    public static function find()
    {
        return new GoodsCategoryQuery(get_called_class());
    }

    //获取Ztree需要的数据
    public static function getZtreeNodes()
    {
        return self::find()->select(['id', 'name', 'parent_id'])->asArray()->all();
    }

    public static function getGoodsCategory()
    {
        return ArrayHelper::map(self::find()->asArray()->all(), 'id', 'name');
    }

    public static function getChildren($id)
    {
        $children = self::find()->where(['parent_id' => $id])->all();
        return $children;
    }

    public static function getIndexGoodsCategory()
    {
        $html = '<div class="cat_bd none">';
        //遍历一级分类
        $categories = self::find()->where(['parent_id' => 0])->all();
        foreach ($categories as $v => $category) {
            //第一个一级分类需要加class = item1
            $html .= '<div class="cat ' . ($v = 0 ? 'item1' : '') . '">
                    <h3><a href="">' . $category->name . '</a> <b></b></h3> 
                   <div class="cat_detail none">';
            $categories2 = $category::find()->children(1)->all();
            foreach ($categories2 as $v2 => $category2) {
                //遍历该一级分类的二级分类
                $html .= '<dl' . ($v2 == 0 ? 'class="dl_1st"' : '') . '>
                    <dt><a href="">' . $category2->name . '</a></dt>
                            <dd>';
                //遍历该二级分类的三级分类
                $categories3 = $category2->children(1)->all();
                foreach ($categories3 as $category3) {
                    $html .= '<a href="">' . $category3->name . '</a>';
                }
                $html .= '</dd>
          </dl>';
            }
            $html .= '</div>
          </div>';
        }
        $html .= '</div>';
        return $html;
    }
}


