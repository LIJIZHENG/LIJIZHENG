<?php
namespace frontend\models;
use yii\db\ActiveRecord;

class LoginForm extends ActiveRecord{
    public $username;
    public $cookie;
    public $password_hash;
//    public $code;//验证码
    public $rememberMe;
    public function rules(){
        return [
            [['username','password_hash'],'required'],
            //验证码
//            ['code','captcha','captchaAction'=>'user/captcha'],
            [['rememberMe','cookie'],'safe']
        ];
    }
    public function attributeLabels(){
        return [
            'username'=>'用户名',
            'password_hash'=>'哈希密码',
            'rememberMe'=>'记住我',
        ];
    }
    public function login($cookie){
        //验证账号
        $user = Member::findOne(['username'=>$this->username]);
        if($user){
            //验证密码
            //调用安全组件的验证密码方法来验证
            if(\Yii::$app->security->validatePassword($this->password_hash,$user->password_hash)){
                if($cookie){
                    $time = 3600;
                }else{
                    $time = 0;
                }
                //将登录标识保存到session
                \Yii::$app->user->login($user,$time);
                //保存登录时间和ip
                $user->last_login_time = time();
                $user->last_login_ip = \Yii::$app->request->userIP;
                $user->save();
                return true;
            }else{
                //给模型添加错误信息
                $this->addError('password_hash','密码错误');
            }
        }else{
            $this->addError('username','账号不存在');
        }
        return false;
    }
}