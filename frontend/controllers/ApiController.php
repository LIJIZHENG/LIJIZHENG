<?php
namespace frontend\controllers;

use backend\models\Article;
use backend\models\ArticleCategory;
use backend\models\Goods;
use backend\models\GoodsCategory;;
use frontend\models\Address;
use frontend\models\Cart;
use frontend\models\Member;
use frontend\models\Order;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\Cookie;
use yii\web\Response;

//API接口(只关注数据,其他跟接口没有关系)
class ApiController extends Controller{
    public $enableCsrfValidation = false;

    //设置响应数据格式(默认html)
    public function init()
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;
        parent::init();
    }

    //登录
    public function actionLogin(){
        $result = [
            'error'=>null,
            'msg'=>'',
            'data'=>[]
        ];
        $username = \Yii::$app->request->post('username');
        $password_hash = \Yii::$app->request->post('password_hash');
        $admin = Member::findOne(['username'=>$username]);
        if($admin){
            if(\Yii::$app->security->validatePassword($password_hash,$admin->password_hash)){
                //验证成功
                \Yii::$app->user->login($admin);
                $result['msg']='登录成功';
                $result['data']=$admin;
            }else{
                //密码错误
                $result['msg']='密码错误';
                $result['error']=1;
            }
        }else{
            $result['msg']='账号不存在';
            $result['error']=1;
        }

        return $result;
    }
    //注册
    public function actionRegister(){
        $result = [
            'error'=>null,
            'msg'=>'',
            'data'=>[]
        ];
        $model = new Member();
        $request = \Yii::$app->request;
        if ($request->isPost) {
            $model->load($request->post(),'');
            if ($model->validate()) {
                $model->password_hash = \Yii::$app->security->generatePasswordHash($model->password_hash);
                $model->created_at = time();
                $model->save(false);
                \Yii::$app->user->login($model);
                $result['msg']='注册成功';
                $result['data']=$model;
            }else{
                //密码错误
                $result['msg']='密码错误';
                $result['error']=1;
            }
        }else{
            $result['msg']='账号不存在';
            $result['error']=1;
        }
        return $result;
    }
    //获取当前登录的用户信息
    //2.收货地址
//添加收货地址
    public function actionAddAddress(){
        $model = new Address();
        $request = \Yii::$app->request;
        $user_id = \Yii::$app->user->id;
        if($request->isPost) {
            $model->load($request->post(), '');
            if ($model->validate()) {
                $model->province = $request->post('cmbProvince');
                $model->city = $request->post('cmbCity');
                $model->count = $request->post('cmbArea');
                $model->user_id = $user_id;
                $model->save();
                $result = [
                    'msg'=> "添加成功",
                    'data'=>[
                        'name'=>$model->name,
                        'province'=>$model->province,
                        'city'=>$model->city,
                        'count'=>$model->count,
                        'detailed_address'=>$model->detailed_address,
                        'phone'=>$model->phone
                    ]
                ];
            } else {
                $result = [
                    'error'=>1,
                    'msg'=>$model->getErrors()
                ];
            }
        }else{
            $result = [
                'error'=>2,
                'msg'=>'请求错误'
            ];
        }
        return $result;
    }
    //修改收货地址
    public function actionUpdateAddress($id){
        $model = Address::findOne(['id'=>$id]);
        $request = \Yii::$app->request;
        if ($request->isPost){
            $model->load($request->post(),'');
            if($model->validate()){
                $model->province = $request->post('cmbProvince');
                $model->city = $request->post('cmbCity');
                $model->count = $request->post('cmbArea');
                $model->save();
                $result = [
                    'msg'=>"修改成功",
                    'data'=>$model
                ];
            }else{
                $result = [
                    'error'=>1,
                    'msg'=>$model->getErrors()
                ];
            }
        }else{
            $result = [
                'error'=>2,
                'msg'=>'请求错误'
            ];
        }
        return $result;
    }
    //删除收货地址
    public function actionDelAddress(){
        $id = \Yii::$app->request->post('id');
        $model = Address::deleteAll(['id'=>$id]);
        if($model){
            $result = [
                'msg'=>'删除成功'
            ];
        }else{
            $result = [
                'error'=>1,
                'msg'=>'删除失败或已被删除'
            ];
        }
        return $result;
    }
    //收货地址列表
    public function actionAddress(){
        $model = Address::find()->all();
        $result = [
            'data'=>$model
        ];
        return $result;
    }

    //获取所有商品分类
    public function actionGoodsCategory(){
        $model = GoodsCategory::find()->orderBy('tree ASC,lft ASC')->all();
        $result = [
            'data'=>$model,
        ];
        return $result;
    }
    //获取某分类的所有子分类
    public function actionGoodsCategoryChildren($id){
        $goods_category = GoodsCategory::findOne(['parent_id'=>$id]);
        $result = [
            'msg'=>"获取成功",
            'data'=>$goods_category
        ];
        return $result;
    }
    //获取某分类的父分类
    public function actionGoodsCategoryParent($id){
        $parent_id = GoodsCategory::findOne(['id'=>$id])->parent_id;
        $goods_category = GoodsCategory::findOne(['id'=>$parent_id]);
        $result = [
            'msg'=>"获取成功",
            'data'=>$goods_category
        ];
        return $result;
    }

    //获取某分类下面的所有商品
    public function actionCategoryGoods($id){
        $goods_category = GoodsCategory::find()->where(['id'=>$id])->one();
        if($goods_category->depth == 2){
            $model = Goods::find()->where(['goods_category_id'=>$id])->all();
        }else{
            $ids = $goods_category->Children()->andWhere(['depth'=>2])->column();
            $model = Goods::find()->where(['in','goods_category_id',$ids])->all();
        }
        $result = [
            'data'=>$model,
        ];
        return $result;
    }
    //获取某品牌下面的所有商品
    public function actionBrandGoods($id){
        $model = Goods::find()->where(['brand_id'=>$id])->all();
        $result = [
            'data'=>$model
        ];
        return $result;
    }

    //获取文章分类
    public function actionArticleCategory(){
        $model = ArticleCategory::find()->all();
        $result = [
            'msg'=>"获取成功",
            'data'=>$model
        ];
        return $result;
    }
    //获取某分类下面的所有文章
    public function actionArticleCategoryArticle($id){
        $article = Article::find()->where(['article_category_id'=>$id])->all();
        $result = [
            'msg'=>"获取成功",
            'data'=>$article
        ];
        return $result;
    }
    //获取某文章所属分类
    public function actionArticleArticleCategory($id){
        $article_category_id = Article::findOne(['id'=>$id])->article_category_id;
        $article_category = ArticleCategory::findOne(['id'=>$article_category_id]);
        $result = [
            'msg'=>"获取成功",
            'data'=>$article_category
        ];
        return $result;

    }

    //添加商品到购物车
    public function actionAddCart($id){
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
                $result = [
                    'msg'=>"添加成功"
                ];
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
                    $result = [
                        'msg'=>"添加成功"
                    ];
                } else {
                    $result = [
                        'error'=>1,
                        'msg'=>$cart->getErrors()
                    ];
                }
            }
        }
        return $result;
    }
    //修改购物车某商品数量
    public function actionUpdateCart(){
        $goods_id = \Yii::$app->request->post('goods_id');
        $amount = \Yii::$app->request->post('amount');
        if(\Yii::$app->user->isGuest){
            //取出cookie中的购物车
            $cookies = \Yii::$app->request->cookies;
            $carts = $cookies->getValue('carts');
            if($carts){
                $carts = unserialize($carts);
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
            $result = [
                'msg'=>"修改成功"
            ];
        }else{
            $cart = Cart::find()->where(['goods_id'=>$goods_id])->andWhere(['member_id'=>\Yii::$app->user->id])->one();
            $cart->amount = $amount;
            $cart->save();
            $result = [
                'msg'=>"修改成功"
            ];
        }
        return $result;
    }
    //删除购物车某商品
    public function actionDelCart(){
        $id = \Yii::$app->request->post('id');
        if (\Yii::$app->user->isGuest){//未登录
            $cookies = \Yii::$app->request->cookies;
            $carts = unserialize($cookies->getValue('carts'));
            unset($carts[$id]);
            $cookies = \Yii::$app->response->cookies;
            $cookie = new Cookie();
            $cookie->name = 'carts';
            $cookie->value = serialize($carts);
            $cookies->add($cookie);
            $result = [
                'msg'=>"清除成功"
            ];
        }else{//登录
            $model = Cart::deleteAll(['goods_id'=>$id]);
            if ($model){
                $result = [
                    'msg'=>"清除成功"
                ];
            }else{
                $result = [
                    'error'=>1,
                    'msg'=>"清除失败"
                ];
            }
        }
        return $result;
    }
    //清空购物车
    public function actionEmpty(){
        if(\Yii::$app->user->isGuest){
            $cookies = \Yii::$app->response->cookies;
            $cookies->remove('carts');
            $result = [
                'msg'=>"清除成功"
            ];
        }else{
            Cart::deleteAll('member_id='.\Yii::$app->user->id);
            $result = [
                'msg'=>"清除成功"
            ];
        }
        return $result;
    }
    //获取购物车所有商品
    public function actionCartGoods(){
        if(\Yii::$app->user->isGuest){//未登录
            $cookies = \Yii::$app->request->cookies;
            $carts = $cookies->getValue('carts');
            if($carts){
                $carts = unserialize($carts);
            }else{
                $carts = [];
            }
            $models = Goods::find()->where(['in','id',array_keys($carts)])->all();
            $result = [
                'msg'=>"获取成功",
                'data'=>[
                    'goods'=>$models,
                    'carts'=>$carts
                ]
            ];
        }else{//登录
            $carts = Cart::find()->where(['member_id'=>\Yii::$app->user->id])->all();
            $carts = ArrayHelper::map($carts,'goods_id','amount');
            $models = Goods::find()->where(['in','id',array_keys($carts)])->all();
            $result = [
                'msg'=>"获取成功",
                'data'=>[
                    'goods'=>$models,
                    'carts'=>$carts
                ]
            ];
        }
        return $result;
    }
    //获取当前用户订单列表
    public function actionOrder(){
        if(\Yii::$app->user->isGuest){
            $result = [
                'error'=>1,
                'msg'=>"请先登录"
            ];
        }else{
            $address = Address::find()->where(['user_id'=>\Yii::$app->user->id])->all();
            $carts = Cart::find()->where(['member_id'=>\Yii::$app->user->id])->all();
            $carts = ArrayHelper::map($carts,'goods_id','amount');
            $models = Goods::find()->where(['in','id',array_keys($carts)])->all();
            $count = 0;
            $price = 0;
            foreach ($models as $v){
                $count += $carts[$v->id];
                $price += $v->shop_price*$carts[$v->id];
            }
            $result = [
                'msg'=>"获取成功",
                'data'=>[
                    'address'=>$address,
                    'carts'=>$carts,
                    'models'=>$models
                ]
            ];
        }
        return $result;
    }
    //获取支付方式
    public function actionPayment(){
        $payment = Order::$payment;
        $result = [
            'msg'=>"获取成功",
            'data'=>$payment,
        ];
        return $result;
    }
    //获取送货方式
    public function actionDelivery(){
        $delivery = Order::$delivery;
        $result = [
            'msg'=>"获取成功",
            'data'=>$delivery,
        ];
        return $result;
    }
    //提交订单
    public  function actionAddOrder(){
        $request = \Yii::$app->request;
        if($request->isPost) {
            $address = Address::find()->where(['id'=>$request->post('address_id'),'user_id'=>\Yii::$app->user->id])->one();
            $order = new Order();
            $order->name = $address->name;
            $order->province = $address->province;
            $order->city = $address->city;
            $order->area = $address->count;
            $order->address = $address->detailed_address;
            $order->tel = $address->phone;
            $order->member_id = \Yii::$app->user->id;
            $order->delivery_name = Order::$delivery[$request->post('delivery')][0];
            $order->delivery_price = Order::$delivery[$request->post('delivery')][1];
            $order->payment_name = Order::$payment[$request->post('payment')][0];
            $order->total = 0;
            $order->status = 1;
            $order->trade_no = 1;
            $order->create_time = time();
            $transaction = \Yii::$app->db->beginTransaction();//开启事务
            try{
                if ($order->save()){//true
                    $carts = Cart::find()->where(['member_id'=>\Yii::$app->user->id])->all();
                    foreach ($carts as $v){
                        //检测商品库存是否足够
                        if($v->amount > $v->goods->stock){
                            $result = [
                                'error'=>1,
                                'msg'=>$v->goods->name.'商品库存不足'
                            ];
                        }
                        $order_goods = new OrderGoods();
                        $order_goods->order_id = $order->id;
                        $order_goods->goods_id = $v->goods_id;
                        $order_goods->goods_name = $v->goods->name;
                        $order_goods->logo = $v->goods->logo;
                        $order_goods->price= $v->goods->shop_price;
                        $order_goods->price= $v->goods->shop_price;
                        $order_goods->amount = $v->amount;
                        $order_goods->total = $order_goods->price*$order_goods->amount;
                        $order_goods->save();
                        $order->total += $order_goods->total;
                    }
                    //删除购物车
                    Cart::deleteAll('member_id='.\Yii::$app->user->id);
                    $order->save();
                }
                //提交事务
                $transaction->commit();
                $result = [
                    'msg'=>"提交成功"
                ];
            }catch (Exception $e){
                //回滚
                $transaction->rollBack();
                //下单失败,跳转回购物车,并且提示商品库存不足
                $result = [
                    'error'=>2,
                    'msg'=>$e->getMessage()
                ];
            }
        }
        return $result;
    }
    //取消订单
    public function actionCancel(){
        $order = Order::deleteAll(['id'=>\Yii::$app->user->id]);
        if ($order){
            $result = [
                'msg'=>"取消成功"
            ];
        }else{
            $result = [
                'error'=>1,
                'msg'=>"取消失败"
            ];
        }
        return $result;
    }

}