<?php
/**
 * Created by PhpStorm.
 * User: xieguangming
 * Date: 2017/11/14
 * Time: 9:36
 */

namespace frontend\controllers;


use frontend\models\Address;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\web\Controller;

class AddressController extends Controller
{
    public $enableCsrfValidation = false;
    //地址
    public function actionIndex(){
        $address = Address::find()->all();
        return $this->render('address',['address'=>$address]);
    }
    public function actionAddress(){
        $request = \Yii::$app->request;
        $data = $request->post();
        $address = new Address();
        $address->load($data,'');
        if (empty($address->status)){
            $address->status = 0;
        }
        if ($address->validate()){
            $address->user_id = \Yii::$app->user->getIdentity();
                if ($address->status == 1 ){
                $defult = Address::findOne(['user_id'=>$address->user_id,'status'=>1]);
                if ($defult){
                    $defult->status = 0;
                    $defult->save();
                }
            }

            $address->save();
            return $this->redirect(['index']);
        }else{
            var_dump($address->getErrors());die;
        }
        }

    //删除地址
    public function actionDel(){
        $id = \Yii::$app->request->post('id');
        $model = Address::deleteAll(['id'=>$id]);
        if($model){
            echo 1;
        }else{
            echo 2;
        }
    }
    //修改地址
    public function actionEdit()
    {
        $id = \Yii::$app->request->post('id');
        $address = Address::findOne($id);
        if($address){
            echo Json::encode($address);
        }else{
            echo 0;
        }

    }
}