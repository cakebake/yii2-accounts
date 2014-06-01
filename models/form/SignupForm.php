<?php

namespace cakebake\accounts\models\form;

use Yii;
use yii\base\Model;
use cakebake\actionlog\model\ActionLog;

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
            ['username', 'filter', 'filter' => 'trim'],
            ['username', 'required'],
            ['username', 'unique', 'targetClass' => Yii::$app->getModule('accounts')->getModel('user', false), 'message' => Yii::t('accounts', 'This username is already registered. Do you already have an account?')],
            ['username', 'string', 'min' => 2, 'max' => 255],

            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'unique', 'targetClass' => Yii::$app->getModule('accounts')->getModel('user', false), 'message' => Yii::t('accounts', 'This email is already registered. Do you already have an account?')],

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
        if ($this->validate()) {
            $user = Yii::$app->getModule('accounts')->getModel('user');
            $user->username = $this->username;
            $user->email = $this->email;
            $user->setPassword($this->password);
            $user->generateAuthKey();
            //$user->setSignupDefaults();

            if ($user->save()) {
                ActionLog::add(ActionLog::LOG_STATUS_INFO, [
                    'info' => $user->username . ' has successfully registered.',
                    'username' => $user->username,
                    'email' => $user->email,
                    'role' => $user->role,
                    'status' => $user->status,
                ], $user->id);

                return $user;
            }

            ActionLog::add(ActionLog::LOG_STATUS_ERROR, [
                'username' => $this->username,
                'email' => $this->email,
                'errors' => $user->errors,
            ]);

            return null;
        }

        ActionLog::add(ActionLog::LOG_STATUS_ERROR, [
            'username' => $this->username,
            'email' => $this->email,
            'errors' => $this->errors,
        ]);

        return null;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'username' => Yii::t('accounts', 'Username'),
            'password' => Yii::t('accounts', 'Password'),
            'email' => Yii::t('accounts', 'Email'),
        ];
    }
}
