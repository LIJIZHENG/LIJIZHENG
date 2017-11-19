<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "order".
 *
 * @property integer $id
 * @property integer $member_id
 * @property string $name
 * @property string $province
 * @property string $city
 * @property string $area
 * @property string $address
 * @property string $tel
 * @property string $delivery_name
 * @property double $delivery_price
 * @property string $payment_name
 * @property string $total
 * @property integer $status
 * @property string $trade_no
 * @property integer $create_time
 */
class Order extends \yii\db\ActiveRecord
{
    public static $delivery = [//配送方式
        1=>['顺丰快递',25],
        2=>['圆通',15],
        3=>['邮政',20],
        4=>['申通',15],
    ];
    public static $payment = [//支付方式
        1=>['在线支付'],
        2=>['货到付款'],
        3=>['上门自提'],
        4=>['线下汇款'],
    ];

}
