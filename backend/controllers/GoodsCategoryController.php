<?php
namespace backend\controllers;
use backend\models\GoodsCategory;
use yii\data\Pagination;
use yii\web\Controller;

class GoodsCategoryController extends Controller{
    public function actionIndex(){
//        $query = GoodsCategory::find();
//        $pager = new Pagination();
//        $pager->totalCount = $query->count();
//        $pager->pageSize=1;
//        $model=$query->limit($pager->limit)->offset($pager->offset)->all();
//        return $this->render('index',['model'=>$model,'pager'=>$pager]);
        $model=GoodsCategory::find();
        return $this->render('index',['model'=>$model]);
    }
}