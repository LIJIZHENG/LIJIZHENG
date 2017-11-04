<?php
namespace backend\controllers;
use backend\models\Article;
use backend\models\ArticleDetail;
use yii\data\Pagination;
use yii\web\Controller;
use yii\web\Request;

class ArticleController extends Controller{
    public function actionIndex(){
        $query = Article::find();
        $pager = new Pagination();
        $pager->totalCount = $query->count();
        $pager->pageSize=1;
        $model=$query->limit($pager->limit)->offset($pager->offset)->all();
        return $this->render('index',['model'=>$model,'pager'=>$pager]);
    }
    public function actionAdd(){
      $model=new Article();
      $_model=new ArticleDetail();
      $request=new Request();
      if($request->isPost){
          $model->load($request->post());
          if($model->validate()){
              $model->create_time = time();
              $model->save(false);
              $_model->content = $model->content;
              $_model->save(false);
              \Yii::$app->session->setFlash('success','添加成功');
              return $this->redirect(['article/index']);
          }else{
              var_dump($model->getErrors());
          }
      }else{
          return $this->render('add',['model'=>$model,'_model'=>'_model']);
          }
    }
    public function actionEdit($id){
      $model=Article::findOne(['id'=>$id]);
      $_model=new ArticleDetail();
      $request=new Request();
      if($request->isPost){
          $model->load($request->post());
          if($model->validate()){
              $model->create_time = time();
              $model->save(false);
              $_model->content = $model->content;
              $_model->save(false);
              \Yii::$app->session->setFlash('success','修改成功');
              return $this->redirect(['article/index']);
          }else{
              var_dump($model->getErrors());
          }
      }else{
          return $this->render('add',['model'=>$model,'_model'=>'_model']);
          }
    }
    public function actionDel(){
        $id = \Yii::$app->request->post('id');
        $model = Article::findOne(['id'=>$id]);
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
        $model = Article::find()->all();
        //展示页面
        return $this->render('dgd',['model'=>$model]);
    }
    public function actionUpdate($id){
        $model=Article::findOne(['id'=>$id]);
        $_model=new ArticleDetail();
        $request=new Request();
        if($request->isPost){
            $model->load($request->post());
            if($model->validate()){
                $model->create_time = time();
                $model->save(false);
                $_model->content = $model->content;
                $_model->save(false);
                \Yii::$app->session->setFlash('success','修改成功');
                return $this->redirect(['article/index']);
            }else{
                var_dump($model->getErrors());
            }
        }else{
            return $this->render('add',['model'=>$model,'_model'=>'_model']);
        }
    }
}