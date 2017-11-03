<?php
namespace backend\controllers;
use backend\models\ArticleCategory;
use yii\data\Pagination;
use yii\web\Controller;
use yii\web\Request;
use yii\web\UploadedFile;

class ArticleCategoryController extends Controller{
    public function actionIndex(){
        $query = ArticleCategory::find();
        $pager = new Pagination();
        $pager->totalCount=$query->count();
        $pager->pageSize=1;
        $model=$query->limit($pager->limit)->offset($pager->offset)->all();
        return $this->render('index',['model'=>$model,'pager'=>$pager]);
    }
    public function actionAdd(){
        $model=new ArticleCategory();
        $request=new Request();
        if($request->isPost){
            $model->load($request->post());
            $model->imgFile=UploadedFile::getInstance($model,'imgFile');
            if ($model->validate()){
                $ext =$model->imgFile->extension;
                $file='/upload/'.uniqid().'.'.$ext;
                $model->imgFile->saveAs(\Yii::getAlias('@webroot').$file,false);
            }
        }
    }
}