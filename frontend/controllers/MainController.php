<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use frontend\models\User;

/**
 * LangController implements the CRUD actions for Lang model.
 */
class MainController extends Controller {
    public function beforeAction($event)
    {
        $this->processPostToSession();
        $this->checkRoute($event);

        if (!Yii::$app->user->isGuest) {
            $this->checkUser($event);
            $this->checkMapping($event);
        }

        return parent::beforeAction($event);
    }

    public function processPostToSession() {
        $postEmail = Yii::$app->request->post('email');
        $postPlanId = Yii::$app->request->post('plan_id');
        $postConversionId = Yii::$app->request->post('conversion_id');

        if (!empty($postEmail)) {
            Yii::$app->session->set('new_member_email', $postEmail);
        }

        if (!empty($postPlanId)) {
            Yii::$app->session->set('payment_plan_id', $postPlanId);
        }

        if (!empty($postConversionId)) {
            Yii::$app->session->set('payment_conversion_id', $postConversionId);
        }
    }

    public function checkUser($event) {
        $route = $event->controller->getRoute();
        $user = Yii::$app->getUser()->getIdentity();

        if ($user->change_password && $route != 'site/changepassword') {
            return Yii::$app->response->redirect(array('site/changepassword'));
        }

        switch ($user->status) {
            case User::STATUS_UNPAID:
                $accessExpire = Yii::$app->params['cancelAccess'];

                if ($route != 'payment/index' && ($user->last_payment_date + $accessExpire) <= time()) {
                    return Yii::$app->response->redirect(array('payment/index'));
                }
                break;
            case User::STATUS_PAID:
            default:
                if ($user->change_password && $route != 'site/changepassword') {
                    return Yii::$app->response->redirect(array('site/changepassword'));
                }/* else if ($route != 'mapping/index' && $route != 'payment/thankyou' && $route != 'site/changepassword') {
                    return Yii::$app->response->redirect(array('mapping/index'));
                }*/
                break;
        }
    }

    public function checkRoute($event) {
        $route = $event->controller->getRoute();

        switch ($route) {
            case 'payment/index':
                $postEmail = Yii::$app->request->post('email');

                $user = Yii::$app->getUser();
                /* logout user on post from payment page if user email not same */
                if ($postEmail != $user->identity->email && !empty($postEmail)) {
                    $user->logout(true);
                    return Yii::$app->response->redirect(array($route));
                }

                break;
            default:
                break;
        }
    }

    public function checkMapping($event) {
        $route = $event->controller->getRoute();

        Yii::$app->MappingComponent->process();

        $steps = Yii::$app->MappingComponent->getSteps();

        if (!empty($steps) && $route != 'mapping/index' && $route != 'site/changepassword') {
            return Yii::$app->response->redirect(array('mapping/index'));
        }
    }


}