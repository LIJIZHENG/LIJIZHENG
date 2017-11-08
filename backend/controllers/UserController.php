<?php
namespace backend\controllers;
use backend\models\User;
use yii\data\Pagination;
use yii\web\Controller;
use yii\web\Request;

class UserController extends Controller{
    public function actionIndex(){
        $query = User::find()->where(['!=','status','-1']);
        $pager = new Pagination();
        $pager->totalCount=$query->count();
        $pager->pageSize=1;
        $model=$query->limit($pager->limit)->offset($pager->offset)->all();
        return $this->render('index',['model'=>$model,'pager'=>$pager]);
    }
    public function actionAdd(){
        $model = new User();
        $request=new Request();
        if($request->isPost){
            $model->load($request->post());
            if($model->validate()){

                $model->created_at=date("Ymd",time());
                $model->password_hash = \Yii::$app->security->generatePasswordHash($model->password_hash);
                $model->save(false);
                \Yii::$app->session->setFlash('success','添加成功');
                return $this->redirect(['user/index']);
            }else{
                var_dump($model->getErrors());
            }
        }else{
            return $this->render('add',['model'=>$model]);
        }
    }
    public function actionEdit($id){
        $model =User::findOne(['id'=>$id]);
        $request=new Request();
        if($request->isPost){
            $model->load($request->post());
            if($model->validate()){

                $model->created_at=date("Ymd",time());
                $model->password_hash = \Yii::$app->security->generatePasswordHash($model->password_hash);
                $model->save(false);
                \Yii::$app->session->setFlash('success','修改成功');
                return $this->redirect(['user/index']);
            }else{
                var_dump($model->getErrors());
            }
        }else{
            return $this->render('add',['model'=>$model]);
        }
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
}