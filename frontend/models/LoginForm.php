<?php
namespace frontend\models ;
use frontend\models\Member;
use yii\base\Model;
use yii\db\ActiveRecord;

class LoginForm extends Model {
public $username;
public $password_hash;
//public $code;//验证码
public $rememberMe;
public function rules(){
    return [
      [['username','password_hash'],'required'],
//     //验证码
//      ['code','captcha','captchaAction'=>'user/captcha'],
        [['rememberMe'],'safe']
        ];
}
public function attributeLabels(){
    return [
        'username'=>'用户名',
        'password_hash'=>'哈希密码',
        'rememberMe'=>'记住我',
    ];
}
    public function login(){
        //验证账号
        $model = Member::findOne(['username'=>$this->username]);
        if($model){
            //验证密码
            //调用安全组件的验证密码方法来验证
            if(\Yii::$app->security->validatePassword($this->password_hash,$model->password_hash)){
                //密码正确 可以登录
                //echo '可以登录';exit;
                //将登录标识保存到session
                $model->last_login_ip=ip2long(\Yii::$app->request->userIP);
                $model->last_login_time = time();
                $model->save(false);
                if($this->rememberMe){
                    \Yii::$app->user->login($model,3600);
                }else{
                    \Yii::$app->user->login($model);
                }
                return true;
            }else{
                //echo '密码错误';exit;
                //给模型添加错误信息
                $this->addError('password_hash','密码错误');
            }
        }else{
            //echo '账号不存在';exit;
            $this->addError('username','账号不存在');
        }
        return false;
    }
}