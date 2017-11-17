<?php

namespace backend\controllers;

use backend\models\Goods;
use backend\models\GoodsCategory;
use yii\web\Response;
use yii\data\Pagination;
class GoodsCategoryController extends \yii\web\Controller
{
    public function actionIndex(){
        $query = GoodsCategory::find();
        $pager = new Pagination();
        $pager->totalCount=$query->count();
        $pager->pageSize=5;
        $model=$query->limit($pager->limit)->offset($pager->offset)->all();
        return $this->render('index',['model'=>$model,'pager'=>$pager]);
    }
    public function actionDel(){
        $id = \Yii::$app->request->post('id');
        $model = GoodsCategory::findOne(['id'=>$id]);
        if($model){
            $model->status=-1;
            $model->update();
            return 'success';
        }else{
            return '该记录不存在或已被删除';
        }
    }
    //添加商品分类
    public function actionAdd(){
        $model = new GoodsCategory();
        //parent_id设置默认值
        $model->parent_id = 0;
        $request = \Yii::$app->request;
        if($request->isPost){
            $model->load($request->post());
            if($model->validate()){
                if($model->parent_id == 0){
                    $model->makeRoot();
                }else{
                    //添加子节点
                    $parent = GoodsCategory::findOne(['id'=>$model->parent_id]);
                    $model->prependTo($parent);
                    \Yii::$app->session->setFlash('success', '添加成功');
                    return $this->redirect(['/goods-category/index']);
                }
            }
        }
        return $this->render('add',['model'=>$model]);
    }
    public function actionEdit($id){
        $model = GoodsCategory::findOne(['id'=>$id]);
        $parent_id=$model->parent_id;
        //parent_id设置默认值
        $model->parent_id = 0;
        $request = \Yii::$app->request;
        if($request->isPost){
            $model->load($request->post());
            if($model->validate()){
                if($model->parent_id == 0){
                 if($parent_id == 0){
                     $model->save();
                 }else{
                     $model->makeRoot();
                 }
                    echo '修改根节点成功';
                }else{
                    //添加子节点
                    $parent = GoodsCategory::findOne(['id'=>$model->parent_id]);
                    $model->prependTo($parent);
                    \Yii::$app->session->setFlash('success', '修改');
                    return $this->redirect(['goods-category/index']);
                }
            }
        }
        return $this->render('add',['model'=>$model]);
    }
    public function actionAjax($filter){
        $this->enableCsrfValidation = false;
        \Yii::$app->response->format = Response::FORMAT_JSON;//将输出自动格式化为json格式
        $request = \Yii::$app->request;
        switch ($filter){
            case 'del'://删除商品分类
                $model = GoodsCategory::findOne($request->post('id'));
                if($model){
                    $model->deleteWithChildren();
                }
                break;
            case 'add':
                $model = new GoodsCategory($request->post());
                if($model->parent_id){
                    $parent = GoodsCategory::findOne(['id'=>$model->parent_id]);
                    $model->prependTo($parent);
                }else{
                    $model->makeRoot();
                }
                return ['id'=>(string)$model->id,'parent_id'=>(string)$model->id,'name'=>(string)$model->name];
                break;
            case 'update'://更新商品分类
                $model = GoodsCategory::findOne($request->post('id'));
                if($model){
                    $model->load($request->post(),'');
                    $model->save();
                }
                break;
            case  'move'://移动商品分类
                $model = GoodsCategory::findOne($request->post('id'));
                $target = GoodsCategory::findOne($request->post('target_id'));
                if($target==null) $target = new GoodsCategory(['id'=>0]);
                switch ($request->post('type')){
                    case 'inner':
                        $model->parent_id=$target->id;
                        if($model->parent_id){
                            $model->appendTo($target);
                        }else{
                            $model->makeRoot();
                        }
                        break;
                    case 'prev':$model->parent_id=$target->parent_id;
                        $model->insertBefore($target);
                        break;
                    case 'next':$model->parent_id=$target->parent_id;
                        $model->insertAfter($target);
                        break;
                }
                break;
            case 'getNodes':return \yii\helpers\ArrayHelper::merge([['id'=>0,'parent_id'=>0,'name'=>'顶级分类']],\backend\models\GoodsCategory::getZtreeNodes());
                break;
        }
    }
    public function actionZtree()
    {
        return $this->render('ztree');
    }
}
