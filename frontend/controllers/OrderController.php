<?php
namespace frontend\controllers;
use backend\models\Goods;
use backend\models\Goods_intro;
use backend\models\GoodsCategory;
use yii\web\Controller;
use yii\web\Cookie;

class OrderController extends Controller
{
    public $enableCsrfValidation = false;
    public function actionDetails($id)
    {
        $model = Goods::findOne($id);
//        var_dump($model);die;
        $img = GoodsCategory::find()->where(['id' => $id])->all();
        $intro = Goods_intro::findOne(['goods_id' => $id]);
        return $this->render('goods', ['model' => $model, 'img' => $img, 'intro' => $intro]);
    }
}