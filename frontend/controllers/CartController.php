<?php
namespace frontend\controllers;
use backend\models\Goods;
use frontend\models\Cart;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\Cookie;
class CartController extends Controller{
    public $enableCsrfValidation = false;
    //购物车页面
    public function actionCart(){
    //需要判断登录和未登录
    if(\Yii::$app->user->isGuest){
        //未登录,购物车数据存放到cookie
        //从cookie中取出购物车数据,调用视图展示
        $cookies =\Yii::$app->request->cookies;
        $carts = $cookies->getValue('carts');
        if($carts){
            $carts = unserialize($carts);
        }else{
            $carts = [];
        }
        //$carts肯定是一个数组
        //获取购物车商品信息
        $models = Goods::find()->where(['in','id',array_keys($carts)])->all();
    }else{
        //已登录,购物车数据存放到数据表
        //$carts= [CART,CART...];  =>['1'=>'3','2'=>'2']
        //$carts需要转换一下格式
        $carts = Cart::find()->where(['member_id'=>\Yii::$app->user->id])->all();
        $carts = ArrayHelper::map($carts,'goods_id','amount');
        $models=Goods::find()->where(['in','id',array_keys($carts)])->all();
    }
    return $this->render('flow1',['carts'=>$carts,'models'=>$models]);
}
    //购物车
    //测试:将测试数据保存到cookie
    public function actionAddCart(){
        $goods_id=\Yii::$app->request->post('goods_id');
        $amount=\Yii::$app->request->post('amount');
        //(商品已经添加到购物车)添加操作是在当前页面执行
        //需要判断登录和未登录
        if(\Yii::$app->user->isGuest){
            //操作cookie购物车
            //获取cookie中购物车数据
            $cookies = \Yii::$app->request->cookies;
            $carts = $cookies->getValue('carts');
            if($carts){
                $carts = unserialize($carts);//$carts = ['1'=>'3','2'=>'2'];
            }else{
                $carts = [];
            }
            //购物车中是否存在该商品,如果存在数量累加 不存在,直接添加
            if(array_key_exists($goods_id,$carts)){
                $carts[$goods_id] += $amount;
            }else{
                $carts[$goods_id] = $amount;
            }
            $cookies = \Yii::$app->response->cookies;
            $cookie = new Cookie();
            $cookie->name = 'carts';
            $cookie->value = serialize($carts);
            $cookies->add($cookie);
        }else{
            //操作数据库购物车
        }

        //跳转到购物车页面
        return $this->redirect(['cart']);
    }
    //删除购物车
    public function actionDel(){
        $id=\Yii::$app->request->post();
        if(\Yii::$app->user->isGuest){//未登录
            $cookies =\Yii::$app->request->cookies;
            $carts=unserialize($cookies->getValue('carts'));
            unset($carts[$id]);
            $cookies=\Yii::$app->request->cookies;
            $cookie=new Cookie();
            $cookie->name='carts';
            $cookie->value=serialize($carts);
            $cookies->add($cookie);
            echo "删除成功";
        }else{//登录
            $model = Cart::deleteAll(['goods_id'=>$id]);
            if($model){
                echo "1";
            }else{
                echo "0";
            }
        }
    }
    //AJAX操作购物车
    //AJAX操作购物车
    public function actionAjaxCart($type){
        //登录操作数据库 未登录操作cookie
        switch ($type){
            case 'change'://修改购物车
                $goods_id = \Yii::$app->request->post('goods_id');
                $amount = \Yii::$app->request->post('amount');
                if(\Yii::$app->user->isGuest){
                    //取出cookie中的购物车
                    $cookies = \Yii::$app->request->cookies;
                    $carts = $cookies->getValue('carts');
                    if($carts){
                        $carts = unserialize($carts);//$carts = ['1'=>'3','2'=>'2'];
                    }else{
                        $carts = [];
                    }
                    //修改购物车商品数量
                    $carts[$goods_id] = $amount;
                    //保存cookie
                    $cookies = \Yii::$app->response->cookies;
                    $cookie = new Cookie();
                    $cookie->name = 'carts';
                    $cookie->value = serialize($carts);
                    $cookies->add($cookie);

                }else{

                }
                break;
            case 'del':

                break;
        }
    }
}