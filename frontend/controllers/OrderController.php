<?php
namespace frontend\controllers;
use backend\models\Goods;
use backend\models\Goods_intro;
use backend\models\GoodsCategory;
use Faker\Provider\Address;
use frontend\models\Cart;
use frontend\models\Order;
use frontend\models\OrderGoods;
use frontend\models\Site;
use yii\db\Exception;
use yii\helpers\ArrayHelper;
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

    //订单列表
    public function actionIndex()
    {
//        if (\Yii::$app->user->isGuest) {
//            return $this->redirect(['member/login']);
//        } else {
            $address = Site::find()->all();
            $carts = Cart::find()->where(['member_id' => \Yii::$app->user->id])->all();
            $carts = ArrayHelper::map($carts, 'goods_id', 'amount');
            $models = Goods::find()->where(['in', 'id', array_keys($carts)])->all();
            $count = 0;
            $price = 0;
            foreach ($models as $v) {
                $count += $carts[$v->id];
                $price += $v->shop_price * $carts[$v->id];
            }
            $request = \Yii::$app->request;
            if ($request->isPost) {
                var_dump($request->post());
                exit;
            }
            return $this->render('index', ['address' => $address, 'carts' => $carts, 'models' => $models, 'count' => $count, 'price' => $price]);
        }
//    }
    //添加订单
    public function actionAdd()
    {
        $request = \Yii::$app->request;
        if ($request->isPost) {
            $address = Address::find()->where(['id' => $request->post('address_id'), 'member_id' => \Yii::$app->user->id])->one();
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
            try {
                if ($order->save()) {
                    $carts = Cart::find()->where(['member_id' => \Yii::$app->user->id])->all();
                    foreach ($carts as $v) {
                        //检测商品库存是否足够
                        if ($v->amount > $v->goods->stock) {
                            throw new Exception($v->goods->name . '商品库存不足');
                        }
                        $order_goods = new OrderGoods();
                        $order_goods->order_id = $order->id;
                        $order_goods->goods_id = $v->goods_id;
                        $order_goods->goods_name = $v->goods->name;
                        $order_goods->logo = $v->goods->logo;
                        $order_goods->price = $v->goods->shop_price;
                        $order_goods->price = $v->goods->shop_price;
                        $order_goods->amount = $v->amount;
                        $order_goods->total = $order_goods->price * $order_goods->amount;
                        $order_goods->save();
                        $order->total += $order_goods->total;
                    }
                    //删除购物车
                    Cart::deleteAll('member_id=' . \Yii::$app->user->id);
                    $order->save();
                    return $this->render('order');
                }
                //提交事务
                $transaction->commit();
            } catch (Exception $e) {
                //回滚
                $transaction->rollBack();
                //下单失败,跳转回购物车,并且提示商品库存不足
                echo $e->getMessage();
                exit;
            }
        }
    }
    //订单详情表
    public function actionGoods()
    {
        $order = Order::find()->where(['member_id' => \Yii::$app->user->id])->all();
        foreach ($order as $value) {
            $order_goods = OrderGoods::find()->where(['order_id' => $value->id])->all();
            foreach ($order_goods as $v) {
                $logos[] = $v->logo;
            }
        }
        return $this->render('goods', ['order' => $order, 'logos' => $logos]);
    }
}