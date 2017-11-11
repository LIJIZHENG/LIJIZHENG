<?php
namespace backend\controllers;
use backend\models\Menu;
use yii\data\Pagination;
use yii\web\Controller;
use yii\web\Request;
use yii\web\Response;

class MenuController extends Controller{
public function actionIndexMenu(){
    $query = Menu::find()->where(['!=','status','-1']);
    $pager = new Pagination();
    $pager->totalCount = $query->count();
    $pager->pageSize=5;
    $model=$query->limit($pager->limit)->offset($pager->offset)->all();
    return $this->render('menu',['model'=>$model,'pager'=>$pager]);
}
public function actionAddMenu(){
   $model = new Menu();
   $request=new Request();
   if($request->isPost){
    $model->load($request->post());
    if($model->validate()){
    $model->save();
        \Yii::$app->session->setFlash('success','添加成功');
        return $this->redirect(['menu/index-menu']);
    }else{
        var_dump($model->getErrors());
    }
   }else{
       return $this->render('add-menu',['model'=>$model]);
   }
}
public function actionEditMenu($id){
   $model =Menu::findOne(['id'=>$id]);
   $request=new Request();
   if($request->isPost){
    $model->load($request->post());
    if($model->validate()){
    $model->save();
        \Yii::$app->session->setFlash('success','修改成功');
        return $this->redirect(['menu/index-menu']);
    }else{
        var_dump($model->getErrors());
    }
   }else{
       return $this->render('edit-menu',['model'=>$model]);
   }
}
public function actionDel(){
    $id = \Yii::$app->request->post('id');
    $model = Menu::findOne(['id'=>$id]);
    if($model){
        $model->status=-1;
        $model->update();
        return 'success';
    }else{
        return '该记录不存在或已被删除';
    }
}
}