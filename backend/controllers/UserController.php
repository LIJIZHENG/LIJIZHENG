<?php
namespace backend\controllers;
use backend\models\LoginForm;
use backend\models\Passwrod;
use backend\models\Role;
use backend\models\User;
use frontend\models\PasswordForm;
use yii\captcha\CaptchaAction;
use yii\data\Pagination;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\Request;
use yii\filters\AccessControl;

class UserController extends Controller {
    public function actionIndex(){
        $query = User::find()->where(['!=','status','-1']);
        $pager = new Pagination();
        $pager->totalCount=$query->count();
        $pager->pageSize=1;
        $model=$query->limit($pager->limit)->offset($pager->offset)->all();
        return $this->render('index',['model'=>$model,'pager'=>$pager]);
    }
    //添加用户
    public function actionAdd(){
        $auth = \yii::$app->authManager;
        $model = new User();
        //设置场景 , 当前场景是SCENARIO_Add场景
//        $model->scenario = User::SCENARIO_Add;

        $request = new Request();
        if ($request->isPost) {
            //接受数据
            $model->load($request->post());
            if ($model->validate()) {

                //使用yii 安全组件来(加密密码)生成密码的密文
                $model->password_hash= \Yii::$app->security->generatePasswordHash($model->password_hash);
                $model->auth_key = \Yii::$app->security->generateRandomString();//随机字符串
                $model->save(false);//保存数据

                //分配角色
                if ($model->role){
                    foreach($model->role as $roleName){
                        $role = $auth->getRole($roleName);
                        $auth->assign($role,$model->id);
                    }
                }

                \Yii::$app->session->setFlash('success', '添加成功');
                return $this->redirect(['user/index']);
            }
        }
        //展示表单页面
        $role = $auth->getRoles();
        $role = ArrayHelper::map($role,'name','name');


        return $this->render('add',['model'=>$model,'role'=>$role]);
    }
    public function actionEdit($id){
        $auth = \yii::$app->authManager;
        $model=User::findOne(['id'=>$id]);
        $model->role = $auth->getRolesByUser($id);
        $request = new Request();
        //回显多选遍历为数组赋值给permissionsid
        if ($auth->getRolesByUser($id)){
            $pers = $auth->getRolesByUser($id);
            foreach ($pers as $v){
                $per[] = $v->name;
            }
            $model->role = $per;
        }
        if ($request->isPost) {
            //接受数据
            $model->load($request->post());
            if ($model->validate()) {

                //使用yii 安全组件来(加密密码)生成密码的密文
                // $model->password_hash= \Yii::$app->security->generatePasswordHash($model->password);
                $model->save();//保存数据
                //删掉原来的角色
                $auth->revokeAll($id);
                //重新分配角色
                if ($model->role){
                    foreach($model->role as $roleName){
                        $role = $auth->getRole($roleName);
                        $auth->assign($role,$id);
                    }
                }

                \Yii::$app->session->setFlash('success', '修改成功');
                return $this->redirect(['user/index']);
            }
        }
        $role = $auth->getRoles();
        $role = ArrayHelper::map($role,'name','name');
        return $this->render('edit',['model'=>$model,'role'=>$role]);
    }
    public function actionDel(){
        $id = \Yii::$app->request->post('id');
        $model = User::findOne(['id'=>$id]);
        if($model){
            $model->status=-1;
            $model->update();
            return 'success';
        }else{
            return '该记录不存在或已被删除';
        }
    }
    public function actionLogin(){
        //1.显示登录表单
        //1.1 实例化表单模型
        $model = new LoginForm();
        $requesr=\Yii::$app->request;

        if($requesr->isPost){
            //表单提交,接收表单数据
            $model->load($requesr->post());
            if($model->validate()){
                //认证//验证账号密码是否正确
                if($model->login()){
                    //提示信息 跳转
                    \Yii::$app->session->setFlash('success','登录成功');
                    //跳转
                    return $this->redirect(['user/index']);
                }
            }
        }
        return $this->render('login',['model'=>$model]);
    }
    public function actions(){
        return [
          'captcha'=>[
              'class'=>CaptchaAction::className()
          ]
        ];
    }
    //注销
    public function actionLognt(){
        \Yii::$app->user->logout();
        return $this->redirect(['logo']);
    }
    //修改密码
    //修改密码
    public function actionPassword(){
        //echo 'pwd';
        //1 显示修改密码表单
        //1.1 实例化表单模型
        $model = new Passwrod();

        //2 接收表单数据,验证旧密码
        $request = \Yii::$app->request;
        if($request->isPost){
            $model->load($request->post());
            if($model->validate()){
                //验证旧密码 新密码和确认新密码一致
                $password_hash = \Yii::$app->user->identity->password_hash;
                if(\Yii::$app->security->validatePassword($model->oldPassword_hash,$password_hash)){
                    //旧密码正确//3 更新当前用户的密码
                    /*$admin = \Yii::$app->user->identity;
                    $admin->password = \Yii::$app->security->generatePasswordHash($model->newPassword);
                    $admin->save(false);*/
                    User::updateAll([
                        'password_hash'=>\Yii::$app->security->generatePasswordHash($model->newPassword_hash)
                    ],
                        ['id'=>\Yii::$app->user->id]
                    );
                    \Yii::$app->user->logout();
                    \Yii::$app->session->setFlash('success','密码修改成功,请重新登录');

                    return $this->redirect(['user/login']);
                }else{
                    //旧密码不正确
                    $model->addError('oldPassword_hash','旧密码不正确');
                }
            }
        }
        //1.2 调用视图
        return $this->render('password',['model'=>$model]);
    }
}