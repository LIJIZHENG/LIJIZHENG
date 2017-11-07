<?php
namespace backend\controllers;
use backend\models\Goods;
use GuzzleHttp\Psr7\UploadedFile;
use yii\data\Pagination;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\Response;

class GoodsController extends Controller{
    public function actionIndex(){
        $query = Goods::find()->where(['!=','status','-1']);
        $pager = new Pagination();
        $pager->totalCount=$query->count();
        $pager->pageSize=1;
        $model=$query->limit($pager->limit)->offset($pager->offset)->all();
        return $this->render('index',['model'=>$model,'pager'=>$pager]);
    }
    public function actionXian(){
        $query = Goods::find()->where(['!=','status','-1']);
        $pager = new Pagination();
        $pager->totalCount=$query->count();
        $pager->pageSize=1;
        $model=$query->limit($pager->limit)->offset($pager->offset)->all();
        return $this->render('xian',['model'=>$model,'pager'=>$pager]);
    }
    public function actionUpload()
    {
        if (\Yii::$app->request->isPost) {
            $imgFile = UploadedFile::getInstanceByName('file');
            //判断是否有文件上传
            if ($imgFile) {
                $fileName = '/upload/' . uniqid() . '.' . $imgFile->extension;
                $imgFile->saveAs(\Yii::getAlias('@webroot') . $fileName, 0);
                //=========将图片上传到七牛云============
                $accessKey = "8lAynpHl_dE0OMQBBDh51AU5tDr_ohHeISv6ovQd";
                $secretKey = "sUK1MSRAdnhG9CB9fWD250txwvN7ur_IDVmTTlwD";
                //对象存储 空间名称
                $bucket = "php0711";
                $domain = '
oyxs2huf5.bkt.clouddn.com';
                $auth = new Goods_intro($accessKey, $secretKey);
                $token = $auth->uploadToken($bucket);
                $filePath = \Yii::getAlias('@webroot') . $fileName;
                $key = $fileName;
                $uploadMgr = new UploadManager();
                list($ret, $err) = $uploadMgr->putFile($token, $key, $filePath);
                if ($err !== null) {
                    return Json::encode(['error' => $err]);
                } else {
                    return Json::encode(['url' => 'http://' . $domain . '/' . $fileName]);
                }
            }
        }
    }
    public function actionAdd(){
        $model = new Goods();
//        var_dump($model);die;
        //parent_id设置默认值
        $model->goods_category_id = 0;
        $request = \Yii::$app->request;
        if($request->isPost){
            $model->load($request->post());
            if($model->validate()){
                if($model->goods_category_id == 0){
                    $model->makeRoot();
                    echo '添加根节点成功';
                }else{
                    $parent = Goods::findOne(['id'=>$model->goods_category_id]);
                    $model->prependTo($parent);
                    echo '添加子节点成功';
                }
            }
        }
        return $this->render('add',['model'=>$model]);
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