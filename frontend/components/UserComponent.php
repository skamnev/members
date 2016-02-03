<?php
namespace frontend\components;

use Yii;
use frontend\models\User;

class UserComponent extends \yii\base\Component {

    public function init() {

        switch(Yii::$app->getUser()->identity->status){
            case User::STATUS_UNPAID:
                return Yii::$app->response->redirect(array('site/pay'));
                break;
            case User::STATUS_PAID:
                return Yii::$app->response->redirect(array('site/pay'));
                break;
            default:
                return Yii::$app->response->redirect(array('site/pay'));
                break;
        }
    }
}