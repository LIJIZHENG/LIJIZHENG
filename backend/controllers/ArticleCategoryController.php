<?php
namespace backend\controllers;
use backend\models\ArticleCategory;
use yii\data\Pagination;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\Request;
use yii\web\UploadedFile;

class ArticleCategoryController extends Controller{
    public function actionIndex(){
        $query = ArticleCategory::find()->where(['!=','status','-1']);
        $pager = new Pagination();
        $pager->totalCount=$query->count();
        $pager->pageSize=1;
        $model=$query->limit($pager->limit)->offset($pager->offset)->all();
        return $this->render('index',['model'=>$model,'pager'=>$pager]);
        }
    public function actionAdd()
        {
        $model = new ArticleCategory();
        $request = new Request();
        if ($request->isPost) {
        $model->load($request->post());
        $model->imgFile = UploadedFile::getInstance($model, 'imgFile');
        if ($model->validate()) {
            $model->save(false);
            \Yii::$app->session->setFlash('success', '添加成功');
            return $this->redirect(['article-category/index']);
        }else{
            var_dump($model->getErrors());
        }
        }else{
        return $this->render('add', ['model' => $model]);
        }
        }
    public function actionEdit($id)
        {
        $model = ArticleCategory::findOne(['id'=>$id]);
        $request = new Request();
        if ($request->isPost) {
        $model->load($request->post());
        $model->imgFile = UploadedFile::getInstance($model, 'imgFile');
        if ($model->validate()) {
            $model->save(false);
            \Yii::$app->session->setFlash('success', '修改成功');
            return $this->redirect(['article-category/index']);
        }else{
            var_dump($model->getErrors());
        }
        }else{
        return $this->render('add', ['model' => $model]);
        }
        }
    public function actionDel(){
        $id = \Yii::$app->request->post('id');
        $model = ArticleCategory::findOne(['id'=>$id]);
        if($model){
            $model->status=-1;
            $model->update();
            return 'success';
        }else{
            return '该记录不存在或已被删除';
        }

    }
    public function actionXian(){
        //查询数据
        $model = ArticleCategory::find()->all();
        //展示页面
        return $this->render('dgd',['model'=>$model]);
    }
    public function actionUpdate($id)
    {
        $model = ArticleCategory::findOne(['id'=>$id]);
        $request = new Request();
        if ($request->isPost) {
            $model->load($request->post());
            $model->imgFile = UploadedFile::getInstance($model, 'imgFile');
            if ($model->validate()) {
                $model->save(false);
                \Yii::$app->session->setFlash('success', '恢复成功');
                return $this->redirect(['article-category/index']);
            }else{
                var_dump($model->getErrors());
            }
        }else{
            return $this->render('update', ['model' => $model]);
        }
    }
}