<?php
namespace frontend\models;

use frontend\models\User;
use linslin\yii2\curl;
use yii\base\Model;
use Yii;

/**
 * Signup form
 */
class SignupForm extends Model
{
    public $username;
    public $email;
    public $password;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            //['username', 'filter', 'filter' => 'trim'],
            //['username', 'required'],
            //['username', 'unique', 'targetClass' => '\frontend\models\User', 'message' => 'This username has already been taken.'],
            //['username', 'string', 'min' => 2, 'max' => 255],

            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\frontend\models\User', 'message' => 'This email address has already been taken.'],

            ['password', 'required'],
            ['password', 'string', 'min' => 6],
        ];
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signup()
    {
        /**
         *
         * POST /9999999/campaigns/99999/subscribers
        Content-Type: application/vnd.api+json
         *
         * {
        "subscribers": [{
        "email": "john@acme.com",
        "utc_offset": 660,
        "double_optin": true,
        "starting_email_index": 0,
        "reactivate_if_removed": true,
        "custom_fields": {
        "name": "John Doe"
        }
        }]
        }
         */


        if ($this->validate()) {
            $user = new User();
            //$user->username = $this->username;
            $user->email = $this->email;
            $user->setPassword($this->password);
            $user->generateAuthKey();
            if ($user->save()) {

                $this->subscribe();

                \Yii::$app->mailer->compose(['html' => 'signUp-html', 'text' => 'signUp-text'], ['user' => $user])
                    ->setFrom([\Yii::$app->params['supportEmail'] => \Yii::$app->name . ' robot'])
                    ->setTo($this->email)
                    ->setSubject('Sign Up to ' . \Yii::$app->name)
                    ->send();

                return $user;
            }
        }

        return null;
    }

    public function subscribe () {
        $url = Yii::$app->params['getdrip']['apiUrl'] . Yii::$app->params['getdrip']['accountId'] . '/' . 'campaigns/' . Yii::$app->params['getdrip']['signup_campaignId'] . '/subscribers';

        $subscribe = json_encode([
            'subscribers' => [
                [
                    "email" => $this->email,
                    "utc_offset" => 660,
                    "double_optin" => true,
                    "starting_email_index" => 0,
                    //"reactivate_if_removed" => true,
                ],
            ]
        ]);

        $curl = new curl\Curl();
        $curl->reset();

        $curl->setOption(CURLOPT_FRESH_CONNECT, true);
        $curl->setOption(CURLOPT_FORBID_REUSE, true);
        $curl->setOption(CURLOPT_RETURNTRANSFER, true);
        $curl->setOption(CURLOPT_FOLLOWLOCATION, true);
        $curl->setOption(CURLOPT_SSL_VERIFYPEER, false);
        $curl->setOption(CURLOPT_SSL_VERIFYHOST, false);
        $curl->setOption(CURLOPT_USERPWD, Yii::$app->params['getdrip']['apiToken'] . ":" . '');
        $curl->setOption(CURLOPT_POSTFIELDS, $subscribe);

        $curl->setOption(CURLOPT_CUSTOMREQUEST, "POST");

        $curl->setOption(CURLOPT_HTTPHEADER, array(
            'Accept:application/json, text/javascript, */*; q=0.01',
            'Content-Type: application/vnd.api+json',
        ));



        $response = $curl->post($url);
    }
}
