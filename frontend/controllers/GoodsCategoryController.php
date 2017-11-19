<?php
/**
 * Created by PhpStorm.
 * User: xieguangming
 * Date: 2017/11/14
 * Time: 14:32
 */

namespace frontend\controllers;


use backend\models\Goods;
use backend\models\GoodsCategory;
use backend\models\GoodsGallery;
use backend\models\GoodsIntro;
use frontend\models\Cart;
use yii\data\Pagination;
use yii\web\Controller;
use yii\web\Cookie;

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
        $intro = GoodsIntro::findOne(['goods_id'=>$id]);
        $request = \Yii::$app->request;
        $cart = new Cart();
        if($request->isPost) {
            $cart->load($request->post(), '');
            if(\Yii::$app->user->isGuest){//未登录
                //获取cookie中购物车的数据
                $cookies = \Yii::$app->request->cookies;
                $carts = $cookies->getValue('carts');
                if($carts){//判断购物车是否有该商品数据
                    $carts = unserialize($carts);
                }else{
                    $carts = [];
                }
                if(array_key_exists($id,$carts)){
                    $carts[$id] += $cart->amount;//有数量加1
                }else{
                    $carts[$id] = $cart->amount;//没有直接添加
                }
                $cookies = \Yii::$app->response->cookies;
                $cookie = new Cookie();
                $cookie->name = 'carts';
                $cookie->value = serialize($carts);
                $cookies->add($cookie);
                return $this->redirect(['cart/index']);
            }else{//登录
                if ($cart->validate()) {
                    $cart->goods_id = $id;
                    $cart_goods_id = Cart::find()->where(['goods_id' => $id])->one();
                    if ($cart_goods_id) {
                        $cart_goods_id->amount += $cart->amount;
                        $cart_goods_id->save();
                    } else {
                        $cart->member_id = \Yii::$app->user->id;
                        $cart->save();
                    }
                    return $this->redirect(['cart/index']);
                } else {
                    var_dump($cart->getErrors());
                    exit;
                }
            }

        }
        return $this->render('goods',['model'=>$model,'img'=>$img,'intro'=>$intro]);
    }
}