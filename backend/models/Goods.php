<?php
namespace backend\models;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use Yii;
use creocoder\nestedsets\NestedSetsBehavior;
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
class Goods extends ActiveRecord{
    public $content;
    /**
     * @inheritdoc
     */
    public function rules(){
        return [
            [['name','logo','content','goods_category_id','market_price','shop_price'],'required'],
            [['brand_id','sn'], 'string'],
            [['sort', 'status','is_on_sale','stock','view_times'], 'integer'],
            [['logo'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    /**
     * @inheritdoc
     */
    public function attributeLabels(){
        return [
            'id' => 'ID',
            'name' => '名称',
            'sn'=>'货号',
            'logo' => 'LOGO',
            'brand_id' => '品牌分类id',
            'market_price'=>'市场价格',
            'shop_price'=>'商品价格',
            'stock'=>'库存',
            'content'=>'文本编译器',
            'is_on_sale'=>'是否在售(1在售 0下架)',
            'sort' => '排序',
            'count'=>'商品数',
            'day'=>'日期',
            'status' => '状态(1正常0回收站)',
            'view_times'=>'浏览次数',
            'goods_category_id'=>'商品分类id',
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
    public static function getZtreeNodes(){
        return self::find()->select(['id','name','parent_id'])->asArray()->all();
    }
    public static function getGoodsCategory(){
        return ArrayHelper::map(self::find()->asArray()->all(),'id','name');
    }
    public static function getChildren($id){
        $children = self::find()->where(['parent_id'=>$id])->all();
        return $children;
    }
}
