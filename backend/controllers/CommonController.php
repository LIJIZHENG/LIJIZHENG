<?php

namespace backend\controllers;


use yii\web\Controller;

class CommonController extends Controller
{
    //RBAC权限控制
    public function beforeAction($action)
    {
        if (!parent::beforeAction($action)) {
            return false;
        }

        $controller = \Yii::$app->controller->id;
        $action = \Yii::$app->controller->action->id;
        $permissionName = $controller.'/'.$action;
        if(!\Yii::$app->user->can($permissionName) && \Yii::$app->getErrorHandler()->exception === null){
            throw new \yii\web\UnauthorizedHttpException('对不起，您没有此操作的权限');
        }
        return true;
    }

}