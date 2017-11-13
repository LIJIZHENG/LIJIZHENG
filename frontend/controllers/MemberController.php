<?php

namespace frontend\controllers;

use frontend\models\LoginForm;
use frontend\models\Member;
use yii\web\Request;

class MemberController extends \yii\web\Controller
{

    public $enableCsrfValidation = false;
    //注册
    public function actionRegist()
    {
        $model = new Member();
        $request = new Request();
        if ($request->isPost) {
//            var_dump($request->isPost);die;
            $model->load($request->post(),'');
            var_dump($request->post());
            if ($model->validate()) {
                $model->save(false);
                \Yii::$app->session->setFlash('success', '添加成功');
                return $this->redirect(['member/member']);
            }else{
                var_dump($model->getErrors());
            }
        }else{
            return $this->render('regist', ['model' => $model]);
        }
    }
    public function actionIndex()
{
return $this->render('index');
}
//    public function actionLogin(){
//        //1.显示登录表单
//        //1.1 实例化表单模型
//        $model = new LoginForm();
//        $request = \Yii::$app->request;
//        if($request->isPost){
//            //表单提交,接收表单数据
//            $model->load($request->post(),'');
//            if($model->validate()){
//                $model->password_hash= \Yii::$app->security->generatePasswordHash($model->password);
//                //认证 //验证账号密码是否正确
//                if($model->login()){
//                    //提示信息  跳转
//                    \Yii::$app->session->setFlash('success','登录成功');
//                    //跳转
//                    return $this->redirect(['index']);
//                }
//            }else{
//                //1.2 调用视图,显示表单
//                return $this->render('login');
//            }
//        }
//
//
//    }
    public function actionLogin(){
        //1.显示登录表单
        //1.1 实例化表单模型
        $model = new LoginForm();
        $requesr=\Yii::$app->request;
        if($requesr->isPost){
//            var_dump($requesr->isPost);die;
            //表单提交,接收表单数据
            $model->load($requesr->post());
//            var_dump($requesr->post());die;
            if($model->validate()){
                $model->password_hash= \Yii::$app->security->generatePasswordHash($model->password_hash);
                //认证//验证账号密码是否正确
                if($model->login()){
                    //提示信息 跳转
                    \Yii::$app->session->setFlash('success','登录成功');
                    //跳转
                    return $this->redirect(['member/member']);
                }
            }
        }
        return $this->render('login',['model'=>$model]);
    }

    //验证用户名唯一
    public function actionCheckName($username){
        if($username=='admin'){
            return 'false';
        }
        return 'true';
    }
}
