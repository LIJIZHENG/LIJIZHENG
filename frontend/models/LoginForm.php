<?php
namespace frontend\models ;
use frontend\models\Member;
use yii\base\Model;
use yii\db\ActiveRecord;

class LoginForm extends Model {
    public $username;
    public $password;
    public $rememberMe;
    public function rules(){
        return [
            [['username','password'],'required'],
            [['rememberMe'],'safe']
        ];
    }
    public function attributeLabels(){
        return [
            'rememberMe'=>'记住我',
        ];
    }
    public function login(){
        //验证账号
        $model = Member::findOne(['username'=>$this->username]);
        if($model){
            //验证密码
            //调用安全组件的验证密码方法来验证
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
        {
                //echo '密码错误';exit;
                //给模型添加错误信息
                $this->addError('password','密码错误');
            }
        }else{
            //echo '账号不存在';exit;
            $this->addError('username','账号不存在');
        }
        return false;
    }
}