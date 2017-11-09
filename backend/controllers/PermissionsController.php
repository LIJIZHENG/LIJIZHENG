<?php
namespace backend\controllers;
use backend\models\Permissions;
use yii\web\Controller;

class PermissionsController extends Controller{
    public function actionPermissions(){
        $permissions=\Yii::$app->authManager->getPermissions();
        return $this->render('permissions',['permissions'=>$permissions]);
    }
    public function actionAddPermissions(){
        $auth = \Yii::$app->authManager;
        $model = new Permissions();
        $request = \Yii::$app->request;
        if($request->isPost){
            $model->load($request->post());
            if($model->validate()){
                $permisson =$auth->createPermission($model->name);
                $permisson->description=$model->description;

                $auth->add($permisson);
                \Yii::$app->session->setFlash('success','添加成功');
                return $this->redirect(['permissions/permissions']);
            }else{
                var_dump($auth->getErrors());
            }
        }else{
            return $this->render('addpermissions',['model'=>$model]);
        }
    }
    public function actionEditPermissions($name){
        $auth = \Yii::$app->authManager;
        $model = new Permissions;
        $permission=\Yii::$app->authManager->getPermission($name);
        $model->name=$permission->name;
        $model->description=$permission->description;
        $request = \Yii::$app->request;
        if($request->isPost){
            $model->load($request->post());
            if($model->validate()){
                $permisson =$auth->createPermission($model->name);
                $permisson->description=$model->description;
                $auth->update($name,$permisson);
                \Yii::$app->session->setFlash('success','修改成功');
                return $this->redirect(['permissions/permissions']);
            }else{
                var_dump($auth->getErrors());
            }
        }else{
            return $this->render('editpermissions',['model'=>$model]);
        }
    }
    public function actionDel($name){
        $permission = \Yii::$app->authManager->getPermission( $name );

        \Yii::$app->authManager->remove( $permission );
        //跳转
        \Yii::$app->session->setFlash('success','删除成功');
        return $this->redirect(['permissions/permissions']);
    }
}