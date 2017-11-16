<?php
/**
 * Created by PhpStorm.
 * User: xieguangming
 * Date: 2017/11/14
 * Time: 14:32
 */

namespace frontend\controllers;


use backend\models\Goods;
use backend\models\Goods_intro;
use backend\models\GoodsCategory;
use backend\models\GoodsGallery;
use backend\models\GoodsIntro;
use yii\data\Pagination;
use yii\web\Controller;

class GoodsCategoryController extends Controller
{
    public $enableCsrfValidation = false;
    //商品分类
    public function actionIndex(){
        $goods_category = GoodsCategory::find()->where(['parent_id'=>0])->all();
        return $this->render('index',['goods_category'=>$goods_category]);
    }
    //商品列表
    public function actionContent($id){
        $goods_category = GoodsCategory::find()->where(['id'=>$id])->one();
        if($goods_category->depth == 2){
            $query = Goods::find()->where(['goods_category_id'=>$id]);
        }else{
            $ids = $goods_category->Children()->andWhere(['depth'=>2])->column();
            $query = Goods::find()->where(['in','goods_category_id',$ids]);
        }
        $pager = new Pagination();
        $pager->pageSize = 8;
        $pager->totalCount = $query->count();
        $model = $query->limit($pager->limit)->offset($pager->offset)->all();
        return $this->render('list',['model'=>$model,'pager'=>$pager]);
    }
    //商品详情
    public function actionDetails($id){
        $model = Goods::findOne($id);
        $img = GoodsGallery::find()->where(['goods_id'=>$id])->all();
        $intro = Goods_intro::findOne(['goods_id'=>$id]);
        return $this->render('goods',['model'=>$model,'img'=>$img,'intro'=>$intro]);
    }
    //添加购物车商品
    public function actionCart(){
        return $this->render('flow');
    }
}