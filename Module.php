<?php

namespace cakebake\accounts;

use Yii;

class Module extends \yii\base\Module
{
    /**
    * @var bool Enable/Disable Login
    */
    public $loginEnabled = true;

    /**
    * @inheritdoc
    */
    public $defaultRoute = 'user';

    /**
    * @var string The location of the email view files. This path must end with a slash!
    */
    public $emailViewsPath = '@accounts/mail/';

    /**
    * @var int The time in seconds, how long the password_reset_token is valid (the link in password_forgot email)
    */
    public $passwordResetTokenExpire = 3600; //3600 = 1h

    /**
     * @var array Models of this module
     */
    private $_models = [];

    /**
    * @inheritdoc
    */
    public function init()
    {
        parent::init();
        Yii::setAlias('@accounts', $this->getBasePath());
        $this->registerTranslations();
        $this->_setModelPaths();
    }

    /**
     * Retrieves the model of the specified ID
     *
     * @param string $id model ID
     * @param boolean $load whether to load the model if it is not yet loaded
     * @return Model|null the model instance, null if the model does not exist
     */
    public function getModel($id, $load = true)
    {
        $id = strtolower($id);

        if (isset($this->_models[$id])) {
            if ($load) {
                Yii::trace("Loading model: $id", __METHOD__);
                if (!is_array($this->_models[$id]) && !isset($this->_models[$id]['class'])) {
                    $this->_models[$id] = ['class' => $this->_models[$id]];
                }

                //return $this->_models[$id] = Yii::createObject($this->_models[$id], [$id, $this]);
                return $this->_models[$id] = Yii::createObject($this->_models[$id]);
            } else {

                return $this->_models[$id];
            }
        }

        return null;
    }

    /**
    * Translating module messages
    */
    public function registerTranslations()
    {
        Yii::$app->i18n->translations['accounts'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'basePath' => '@accounts/messages',
            'sourceLanguage' => 'en-US',
        ];
    }

    /**
    * Sets the model paths by model ID
    */
    protected function _setModelPaths() {
        $this->_models = [
            //base
            'account' => 'cakebake\accounts\models\Account',
            'user' => 'cakebake\accounts\models\User',
            //user forms
            'signup' => 'cakebake\accounts\models\form\SignupForm',
            'login' => 'cakebake\accounts\models\form\LoginForm',
            'forgot_password' => 'cakebake\accounts\models\form\ForgotPasswordForm',
            'reset_password' => 'cakebake\accounts\models\form\ResetPasswordForm',
        ];
    }
}
