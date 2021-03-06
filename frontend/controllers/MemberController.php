<?php
namespace frontend\controllers;
use backend\models\Goods;
use backend\models\GoodsCategory;
use backend\models\Permissions;
use Faker\Provider\Address;
use frontend\components\Sms;
use backend\models\Goods_intro;
use frontend\models\Cart;
use frontend\models\LoginForm;
use frontend\models\Member;
use frontend\models\Site;
use frontend\models\SphinxClient;
use yii\helpers\ArrayHelper;
use yii\web\Request;
class MemberController extends \yii\web\Controller
{
    public $enableCsrfValidation = false;
    //注册
    public function actionRegist()
    {
        $model = new Member();
        $request = \Yii::$app->request;
        if ($request->isPost) {
//            var_dump($request->isPost);die;
            $model->load($request->post(),'');
//            var_dump($request->post());
            if ($model->validate()) {
                $model->password_hash = \Yii::$app->security->generatePasswordHash($model->password_hash);
                $model->created_at = time();

                $model->save(false);
                return $this->redirect(['member/regist']);
            }else{
                var_dump($model->getErrors());die;
            }
        }
            return $this->render('register', ['model' => $model]);
    }
    //AJAX发送短信验证 后台AJAX发送短信功能:
    public function actionAjaxSms($phone){
        $code=rand(1000,9999);
        //接收请求手机号码
        $phone='15983193712';
        //发送短信
        $response=Sms::sendSms(
            "小基网",//短信签名
            "SMS_109395480",//短信模板编号
            $phone,//短信接收者
            Array(
                //短信模板字段得值
                "code"=>$code,
            )
        );
        //根据$response结果判断是否发送成功
//        $response->Code
        //保存验证码(SESSION或)REDIS
        $session = \Yii::$app->session;
        $session->set('captcha_'.$phone,$code,10*60);
//        $redis = new \Redis();
//        $redis->connect('127.0.0.1');
//        $redis->set('captcha_'.$phone,rand(1000,9999),10*60);
        return 'success';//'fail
    }
    //AJAX验证短信
    public function actionCheckSms($sms){
        //从redis获取验证码
        //返回对比结果
        //验证结果
        $requset = new Request();
        $phone=$requset->post('tel');
        $session = \Yii::$app->session;
        $code=$session->get('captcha_'.$phone);
        if($code == $sms){
            return true;
        }else{
            return false;
        };
    }
    //测试阿里大于短信发送功能
    //登录
    public function actionLogin(){
        //登录表单
        $model = new LoginForm();
        $request = \Yii::$app->request;
        if($request->isPost) {
            $model->load($request->post(), '');
            $model->rememberMe = $request->post('rememberMe');
           if ($model->validate() && $model->check()){
               return $this->redirect(['member/index']);
           }else{
               echo '登录失败';
           }
        }
        return $this->render('login');
    }
    public function actionLognt(){
        \Yii::$app->user->logout();
        return $this->redirect(['login']);
    }
    //验证用户名唯一
    public function actionCheckName($username){
        if($username=='admin'){
            return 'false';
        }
        return 'true';
    }
    public function actionAdd(){
        $model = new  Site();
        $request = new Request();
        if ($request->isPost) {
//            var_dump($request->isPost);die;
            $model->load($request->post(),'');
//            var_dump($request->post());
            if ($model->validate()) {
                $model->save(false);
                \Yii::$app->session->setFlash('success', '添加成功');
                return $this->redirect(['order/index']);
            }else{
                var_dump($model->getErrors());
            }
        }else{
            return $this->render('address', ['model' => $model]);
        }
    }
    public function actionEdit($id)
    {
        $model = Site::findOne(['id'=>$id]);
        $request = new Request();
        if ($request->isPost) {
            $model->load($request->post(),'');
            if ($model->validate()) {
                $model->save(false);
                \Yii::$app->session->setFlash('success', '修改成功');
                return $this->redirect(['member/index']);
            }else{
                var_dump($model->getErrors());
            }
        }else{
            return $this->render('editress', ['model' => $model]);
        }
    }
    //中文分词
    //中文分词搜索测试
    public function actionSearch($keyword){
        $cl = new SphinxClient();
        $cl->SetServer ( '127.0.0.1', 9312);//设置sphinx的searchd服务信息
        $cl->SetConnectTimeout ( 10 );//超时
        $cl->SetArrayResult ( true );//结果以数组形式返回
        $cl->SetMatchMode ( SPH_MATCH_EXTENDED2);//设置匹配模式
        $cl->SetLimits(0, 1000);//设置分页
        $info = $keyword;//查询关键字
        //进行查询   Query(查询关键字,使用的索引)
        $res = $cl->Query($info, 'goods');
        if(isset($res['matches'])){
            //查询到结果
            $ids = ArrayHelper::map($res['matches'],'id','id');
            $models = Goods::find()->where(['in','id',$ids])->all();
//            var_dump($models);
        }else{
           return $this->redirect('index');
        }
        return $this->render('member/goods-category',['models'=>$models]);
    }
    public function actionIndex(){
        $model=Goods::find()->all();
        return $this->render('index',['model'=>$model]);
    }
    public function actionDel($id){
        //根据id删除数据
        Site::findone(['id'=>$id])->delete();
        //跳转
        \Yii::$app->session->setFlash('success','删除成功');

        return $this->redirect(['member/index']);
    }
    public function actionGoodsCategory($id){
        $model= GoodsCategory::find()->roots()->all();
     return $this->render('list',['model'=>$model]);
    }
}
