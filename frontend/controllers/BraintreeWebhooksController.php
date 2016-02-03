<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use frontend\models\User;

/**
 * LangController implements the CRUD actions for Lang model.
 */
class BraintreeWebhooksController extends Controller {

    var $_data = [];

    public function init() {

        foreach (['merchantId', 'publicKey', 'privateKey', 'environment'] as $attribute) {
            if (\Yii::$app->get('braintree')->$attribute === null) {
                throw new InvalidConfigException(strtr('"{class}::{attribute}" cannot be empty.', [
                    '{class}' => static::className(),
                    '{attribute}' => '$' . $attribute
                ]));
            }
            \Braintree_Configuration::$attribute(\Yii::$app->get('braintree')->$attribute);
        }
    }

    public function beforeAction($action) {

        if(Yii::$app->request->post()) {
            $this->_data = Yii::$app->request->post();
            $this->enableCsrfValidation = false;
        }
        return parent::beforeAction($action);
    }

    public function actionCancel() {

        if (isset($this->_data["bt_signature"]) && isset($this->_data["bt_payload"])) {
            $webhookNotification = \Braintree_WebhookNotification::parse(
                $this->_data["bt_signature"], $this->_data["bt_payload"]
            );

            if (isset($webhookNotification->subscription->transactions[0]->customer['id'])) {
                $this->_updateUserStatus($webhookNotification->subscription->transactions[0]->customer['id'], User::STATUS_UNPAID);
            }

            $message =
                "[Webhook Received " . $webhookNotification->timestamp->format('Y-m-d H:i:s') . "] "
                . "Kind: " . $webhookNotification->kind . " | "
                . "Subscription: " . $webhookNotification->subscription->id . "\n";

            file_put_contents("webhook.log", $message, FILE_APPEND);
        }
    }

    public function actionSuccess() {

        if (isset($this->_data["bt_signature"]) && isset($this->_data["bt_payload"])) {
            $webhookNotification = \Braintree_WebhookNotification::parse(
                $this->_data["bt_signature"], $this->_data["bt_payload"]
            );

            if (isset($webhookNotification->subscription->transactions[0]->customer['id'])) {
                $this->_updateUserStatus($webhookNotification->subscription->transactions[0]->customer['id'], User::STATUS_PAID);
            }

            $message =
                "[Webhook Received " . $webhookNotification->timestamp->format('Y-m-d H:i:s') . "] "
                . "Kind: " . $webhookNotification->kind . " | "
                . "Subscription: " . $webhookNotification->subscription->id . "\n";

            file_put_contents("webhook.log", $message, FILE_APPEND);
        }
    }

    private function _updateUserStatus($id, $status ) {
        $user = User::findOne(['braintree_customer_id' => $id]);

        if ($user) {
            $old_status = $user->status;
            $user->status = $status;

            if ($user->save()) {
                file_put_contents("webhook.log", "User $id status updated from '$old_status' to '$status'", FILE_APPEND);
            }
        }
    }
}