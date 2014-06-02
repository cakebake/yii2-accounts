<?php

namespace cakebake\accounts\models\form;

use Yii;
use yii\base\Model;
use cakebake\actionlog\model\ActionLog;

/**
 * LoginForm is the model behind the login form.
 */
class LoginForm extends Model
{
    public $username;
    public $password;
    public $rememberMe = true;

    private $_user = false;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['username', 'password'], 'required'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     */
    public function validatePassword()
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError('password', Yii::t('accounts', 'Incorrect username or password. Please try again.'));
            }
        }
    }

    /**
     * Logs in a user using the provided username and password.
     *
     * @return boolean whether the user is logged in successfully
     */
    public function login()
    {
        $user = $this->getUser();
        $model = Yii::$app->getModule('accounts')->getModel('user', false);

        if ($this->validate()) {
            ActionLog::add(ActionLog::LOG_STATUS_INFO, null, $user->id);

            return Yii::$app->user->login($user, $this->rememberMe ? 3600 * 24 * 30 : 0);
        } else {
            ActionLog::add(ActionLog::LOG_STATUS_ERROR, (is_object($user) ? $user->getNicename() : 'Unknown user') . ' failed login-form validation', is_object($user) ? $user->id : 0);

            return false;
        }
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    public function getUser()
    {
        if ($this->_user === false) {
            $model = Yii::$app->getModule('accounts')->getModel('user', false);
            $this->_user = $model::findByUsername($this->username);
        }

        return $this->_user;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'username' => Yii::t('accounts', 'Username'),
            'password' => Yii::t('accounts', 'Password'),
            'rememberMe' => Yii::t('accounts', 'Remember Me'),
        ];
    }
}
