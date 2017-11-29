<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "address".
 *
 * @property integer $id
 * @property string $name
 * @property string $province
 * @property string $city
 * @property string $count
 * @property string $detailed_address
 * @property string $phone
 * @property integer $user_id
 */
class Address extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'address';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id'], 'integer'],
            [['name'], 'string', 'max' => 20],
            [['province', 'city', 'area', 'detail'], 'string', 'max' => 255],
            [['phone'], 'string', 'max' => 11,'message'=>"联系电话不合法"],
            ['name','required','message'=>"收货人不能为空"],
            ['phone','required','message'=>"联系电话不能为空"],
            ['detail','required','message'=>"详细地址不能为空"],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '收货人',
            'province' => '省',
            'city' => '市',
            'area' => '县/区',
            'detail' => '详细地址',
            'phone' => '电话号码',
            'user_id' => '用户ID',
        ];
    }
}
