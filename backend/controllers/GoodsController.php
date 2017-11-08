<?php
namespace backend\controllers;
use backend\models\Goods;
use backend\models\Goods_day_count;
use backend\models\Goods_intro;
use backend\models\GoodsCategory;
use backend\models\GoodsGallery;
use GuzzleHttp\Psr7\UploadedFile;
use yii\data\Pagination;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\Request;
use yii\web\Response;

class GoodsController extends Controller{
    public $enableCsrfValidation = false;
    public function actionIndex(){
        $query = Goods::find()->where(['!=','status','-1']);
        $pager = new Pagination();
        $pager->totalCount=$query->count();
        $pager->pageSize=1;
        $model=$query->limit($pager->limit)->offset($pager->offset)->all();
        $condition=[];
        if(!empty($_POST['goods_category_id'])){
            $condition[] = "goods_category_id = {$_POST['goods_category_id']}";
        }
        if(!empty($_POST['status'])){
            $condition[]="(status & {$_POST['status']})>0";
        }
        if(!empty($_POST['is_on_sale'])){
            $condition[]="is_on_sale={$_POST['is_on_sale']}-1";
        }
        if(!empty($_POST['keyword'])){
            $condition[] = "(name like '%{$_POST['keyword']}%' or sn like '%{$_POST['keyword']}%')";
        }
//        var_dump($condition);die;
//        $_model = Goods::find()->andwhere($condition)->all();
        $category=GoodsCategory::find()->all();
//        var_dump($_model);die;
        return $this->render('index',['model'=>$model,'pager'=>$pager,'category'=>$category]);
    }
    public function actionAddgoods(){
        $query = GoodsGallery::find();
        $pager = new Pagination();
        $pager->totalCount=$query->count();
        $pager->pageSize=1;
        $model=$query->limit($pager->limit)->offset($pager->offset)->all();
        return $this->render('addgoods',['model'=>$model,'pager'=>$pager]);
    }
    public function actionAdd(){
        $model=new Goods();
        $_model=new Goods_intro();
        $day = date("Y-m-d",time());
        $count=Goods_day_count::findOne(['day'=>$day]);
//        var_dump($count);exit;
        $request=new Request();
        if($request->isPost){
            $model->load($request->post());
            if($model->validate()){
//                var_dump(1);exit;
                $day2 = date("Ymd",time());
                if($day==0){
                }else{
                 $model->sn = str_pad($count->count+1,5,"0",STR_PAD_RIGHT);
                 $day2.$model->sn;
                }
                $count->save(false);
                $model->create_time = time();
                $model->save(false);
                $_model->content = $model->content;
                $_model->save(false);
                \Yii::$app->session->setFlash('success','添加成功');
                return $this->redirect(['goods/index']);
            }else{
                var_dump($model->getErrors());
            }
        }else{
            return $this->render('add',['model'=>$model,'_model'=>$_model,'count'=>$count]);
        }
    }
    public function actionDel(){
        $id = \Yii::$app->request->post('id');
        $brand = Goods::findOne(['id'=>$id]);
        if($brand){
            $brand->status=-1;
            $brand->update();
            return 'success';
        }else{
            return '该记录不存在或已被删除';
        }
    }
    public function actionEdit($id){
        $model=Goods::findOne(['id'=>$id]);
        $_model=Goods_intro::findOne(['id'=>$id]);
        $request=new Request();
        if($request->isPost){
            $model->load($request->post());
//            $_model->load($request->post());
            if($model->validate()){
                $model->create_time = time();
                $model->save(false);
                $_model->content = $model->content;
                $_model->save(false);
                \Yii::$app->session->setFlash('success','修改成功');
                return $this->redirect(['goods/index']);
            }else{
                var_dump($model->getErrors());
            }
        }else{
            return $this->render('add',['model'=>$model,'_model'=>$_model]);
        }
    }
    //商品分类管理AJAX版
    public function actionAjax($filter){
        $this->enableCsrfValidation = false;
        \Yii::$app->response->format = Response::FORMAT_JSON;//将输出自动格式化为json格式
        $request = \Yii::$app->request;
        switch ($filter){
            case 'del'://删除商品分类
                $model = Goods::findOne($request->post('id'));
                if($model){
                    $model->deleteWithChildren();
                }
                break;
            case 'add'://添加商品分类
                $model = new Goods($request->post());
                if($model->parent_id){
                    //非顶级分类(子分类)
                    $parent = Goods::findOne(['id'=>$model->parent_id]);
                    $model->preprependTo($parent);
                }else{
                    //顶级分类
                    $model->makeRoot();
                }
                return ['id'=>(string)$model->id,'goods_category_id'=>(string)$model->id,'name'=>(string)$model->name];
                break;
            case 'update'://更新商品分类
                $model = Goods::findOne($request->post('id'));
                if($model){
                    $model->load($request->post(),'');
                    $model->save();
                }
                break;
            case  'move'://移动商品分类
                $model = Goods::findOne($request->post('id'));
                $target = Goods::findOne($request->post('target_id'));
                if($target==null) $target = new Goods(['id'=>0]);
                switch ($request->post('type')){
                    case 'inner':
                        $model->parent_id=$target->id;
                        if($model->parent_id){
                            $model->appendTo($target);
                        }else{
                            $model->makeRoot();
                        }
                        //$model->prependTo($target);
                        break;
                    case 'prev':
                        $model->parent_id=$target->parent_id;
                        $model->insertBefore($target);
                        break;
                    case 'next':
                        $model->parent_id=$target->parent_id;
                        $model->insertAfter($target);
                        break;
                }
                break;
            case 'getNodes'://获取所有分类节点数据
                //return GoodsCategory::find()->select(['id','parent_id','name'])->asArray()->all();
                return \yii\helpers\ArrayHelper::merge([['id'=>0,'goods_category_id'=>0,'name'=>'顶级分类']],\backend\models\Goods::getZtreeNodes());
                break;
        }
    }
    public function actionZtree()
    {
        return $this->render('ztree');
    }
    public function actions()
    {
        return [
            'upload' => [
                'class' => 'kucha\ueditor\UEditorAction',
            ]
        ];
    }
}