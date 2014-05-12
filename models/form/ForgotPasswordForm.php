<?php

namespace cakebake\accounts\models\form;

use Yii;
use yii\base\Model;

/**
 * Password forgotten form
 */
class ForgotPasswordForm extends Model
{
    public $email;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        $model = Yii::$app->getModule('accounts')->getModel('user', false);
        return [
            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'exist',
                'targetClass' => $model,
                'filter' => ['status' => $model::STATUS_ACTIVE],
                'message' => 'There is no user with such email.'
            ],
        ];
    }

    /**
     * Sends an email with a link, for resetting the password.
     *
     * @return boolean whether the email was send
     */
    public function sendEmail()
    {
        $model = Yii::$app->getModule('accounts')->getModel('user', false);
        $user = $model::findOne([
            'status' => $model::STATUS_ACTIVE,
            'email' => $this->email,
        ]);

        if ($user) {
            $user->generatePasswordResetToken();
            if ($user->save()) {
                return Yii::$app->mail->compose(Yii::$app->getModule('accounts')->emailViewsPath . 'forgot_password_link', ['user' => $user])
                    ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name])
                    ->setTo($this->email)
                    ->setSubject('Password reset for ' . Yii::$app->name)
                    ->send();
            }
        }

        return false;
    }
}
