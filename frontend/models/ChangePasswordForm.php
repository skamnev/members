<?php
namespace frontend\models;

use frontend\models\User;
use yii\base\InvalidParamException;
use yii\base\Model;
use Yii;

/**
 * Password reset form
 */
class ChangePasswordForm extends Model
{
    public $oldpass;
    public $newpass;
    public $repeatnewpass;

    public function rules(){
        return [
            [['oldpass','newpass','repeatnewpass'],'required'],
            ['oldpass','findPasswords'],
            ['repeatnewpass','compare','compareAttribute'=>'newpass'],
        ];
    }

    public function findPasswords($attribute, $params){
        $validatePassword = Yii::$app->getUser()->getIdentity()->validatePassword($this->oldpass);

        if(!$validatePassword)
            $this->addError($attribute,'Old password is incorrect');
    }

    public function attributeLabels(){
        return [
            'oldpass'=>Yii::t('frontend','Old Password'),
            'newpass'=>Yii::t('frontend','New Password'),
            'repeatnewpass'=>Yii::t('frontend','Repeat New Password'),
        ];
    }
}
