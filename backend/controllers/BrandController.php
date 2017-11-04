<?php
namespace backend\controllers;
use yii\web\Controller;
use backend\models\Brand;
use yii\web\Request;
use yii\web\UploadedFile;
use yii\data\Pagination;
use yii\data\Sort;
class BrandController extends Controller{
    public function actionIndex(){
        $query = Brand::find();
        $pager = new Pagination();
        $pager->totalCount = $query->count();
        $pager->pageSize=1;
        $brand=$query->limit($pager->limit)->offset($pager->offset)->all();
        return $this->render('brand',['brand'=>$brand,'pager'=>$pager]);
    }
    public function actionAdd(){
        $brand = new Brand();
        $request=new Request();
        if($request->isPost){
            $brand->load($request->post());
            $brand->imgFile=UploadedFile::getInstance($brand,'imgFile');
            if($brand->validate()){
//                var_dump($brand);die;
                $ext = $brand->imgFile->extension;
                $file='/upload/'.uniqid().'.'.$ext;
                $brand->imgFile->saveAs(\Yii::getAlias('@webroot').$file,false);
                $brand->logo=$file;
                $brand->save();
                \Yii::$app->session->setFlash('success','添加成功');
                return $this->redirect(['brand/index']);
            }else{
                var_dump($brand->getErrors());
            }
        }else{
            return $this->render('add',['brand'=>$brand]);
        }
    }
    public function actionEdit($id){
        $brand =Brand::findOne(['id'=>$id]);
        $request=new Request();
        if($request->isPost){
            $brand->load($request->post());
            $brand->imgFile=UploadedFile::getInstance($brand,'imgFile');
            if($brand->validate()){
                $ext = $brand->imgFile->extension;
                $file='/upload/'.uniqid().'.'.$ext;
                $brand->imgFile->saveAs(\Yii::getAlias('@webroot').$file);
                $brand->logo=$file;
                $brand->save(false);
                \Yii::$app->session->setFlash('success','修改成功');
                return $this->redirect(['brand/index']);
            }else{
                var_dump($brand->getErrors());
            }
        }else{
            return $this->render('add',['brand'=>$brand]);
        }
    }
    public function actionDel(){
        $id = \Yii::$app->request->post('id');
        $brand = Brand::findOne(['id'=>$id]);
        if($brand){
            $brand->status=-1;
            $brand->update();
            return 'success';
        }else{
            return '该记录不存在或已被删除';
        }
    }
    public function actionXian(){
        //查询数据
        $model = Brand::find()->all();
        //展示页面
        return $this->render('dgd',['model'=>$model]);
    }
    public function actionUpdate($id){
        $brand =Brand::findOne(['id'=>$id]);
        $request=new Request();
        if($request->isPost){
            $brand->load($request->post());
            $brand->imgFile=UploadedFile::getInstance($brand,'imgFile');
            if($brand->validate()){
                $ext = $brand->imgFile->extension;
                $file='/upload/'.uniqid().'.'.$ext;
                $brand->imgFile->saveAs(\Yii::getAlias('@webroot').$file);
                $brand->logo=$file;
                $brand->save(false);
                \Yii::$app->session->setFlash('success','修改成功');
                return $this->redirect(['brand/index']);
            }else{
                var_dump($brand->getErrors());
            }
        }else{
            return $this->render('update',['brand'=>$brand]);
        }
    }
}