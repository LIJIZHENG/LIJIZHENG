<?php
namespace backend\controllers;
use backend\models\Role;
use yii\helpers\ArrayHelper;
use yii\web\Controller;

class RoleController extends Controller {
    public function actionRole(){
        $model = \Yii::$app->authManager->getRoles();
        return $this->render('role',['model'=>$model]);
    }
    public function actionAddRole(){
        $auth = \Yii::$app->authManager;
        $model = new Role();
        $request = \Yii::$app->request;
        if($request->isPost){
            $model->load($request->post());
            if($model->validate()){
                //创建角色
                $role = $auth->createRole($model->name);
                $role->description = $model->description;
                $auth->add($role);//角色添加到数据表
                foreach ($model->permissions as $permissionName){
                    $permission = $auth->getPermission($permissionName);//根据权限的名称获取权限对象
                    //给角色分配权限
                    $auth->addChild($role,$permission);
                }
                return $this->redirect(['role/role']);
            }
        }
        $permissions = $auth->getPermissions();
        //var_dump($permissions);exit;
        $permissions = ArrayHelper::map($permissions,'name','description');
        return $this->render('add-role',['model'=>$model,'permissions'=>$permissions]);
    }
    public function actionEditRole($name){
        $auth = \Yii::$app->authManager;
        $model = new Role();
        $role=\Yii::$app->authManager->getRole($name);
        $model->name=$role->name;
        $model->description=$role->description;
        $model->permissions=array_keys($auth->getPermissionsByRole($name));
        $request = \Yii::$app->request;
        if($request->isPost){
            $model->load($request->post());
            if($model->validate()){
                //创建角色
                $role->name=$model->name;
                $role->description = $model->description;
                $auth->update($name,$role);//角色添加到数据表
                $auth->removeChildren($role);
                foreach ($model->permissions as $permissionName){
                    $permission = $auth->getPermission($permissionName);//根据权限的名称获取权限对象
                    //给角色分配权限
                    $auth->addChild($role,$permission);
                }
                return $this->redirect(['role/role']);
            }
        }
        $permissions = $auth->getPermissions();
        //var_dump($permissions);exit;
        $permissions = ArrayHelper::map($permissions,'name','description');
        return $this->render('add-role',['model'=>$model,'permissions'=>$permissions]);
    }
    public function actionDel($name){
        $role = \Yii::$app->authManager->getRole($name);
//           var_dump($name);die;
        \Yii::$app->authManager->remove($role);
        //跳转
        \Yii::$app->session->setFlash('success','删除成功');
        return $this->redirect(['role/role']);
    }
}