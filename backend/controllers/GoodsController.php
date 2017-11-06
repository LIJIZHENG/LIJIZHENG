<?php
namespace backend\controllers;
use backend\models\Goods;
use yii\data\Pagination;
use yii\web\Controller;
use yii\web\Request;
use yii\web\UploadedFile;

class GoodsController extends Controller{
    public function actionIndex(){
        $query =Goods::find();
        $pager = new Pagination();
        $pager->totalCount=$query->count();
        $pager->pageSize=1;
        $model=$query->limit($pager->limit)->offset($pager->offset)->all();
        return $this->render('index',['model'=>$model,'pager'=>$pager]);
    }
    public function actionAdd(){
       $model = new Goods();
       $request = new Request();
       if($request->isPost){
           $model->load($request->post());
           $model->imgFile=UploadedFile::getInstance($model,'imgFile');
           if($model->validate()){
               $ext = $model->imgFile->extension;
               $file='/upload/'.uniqid().'.'.$ext;
               $model->imgFile->saveAs(\Yii::getAlias('@webroot').$file);
               $model->logo = $file;
               $model->create_time = time();
               $model->save();
               \Yii::$app->session->setFlash('success','添加成功');
               return $this->redirect(['goods/index']);
           }else{
               var_dump($model->getErrors());
           }
       }else{
           return $this->render('add',['model'=>$model]);
       }
    }
}