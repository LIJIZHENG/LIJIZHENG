<?php
namespace backend\controllers;
use yii\helpers\Json;
use yii\web\Controller;
use backend\models\Brand;
use yii\web\Request;
use yii\web\UploadedFile;
use yii\data\Pagination;
use yii\data\Sort;
// 引入鉴权类
use Qiniu\Auth;

// 引入上传类
use Qiniu\Storage\UploadManager;
class BrandController extends Controller{
    public $enableCsrfValidation = false;
    public function actionIndex(){
        $query = Brand::find()->where(['!=','status','-1']);
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
//            var_dump($request->post());die;
            $brand->load($request->post());
            if($brand->validate()){
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
                $auth = new Auth($accessKey, $secretKey);
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
}
