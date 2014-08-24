<?php

namespace cakebake\accounts;

use Yii;
use yii\helpers\ArrayHelper;

class Module extends \yii\base\Module
{
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
    * @var bool Option to enable/disable the "login / forgot-password / reset-password" actions
    */
    public $enableLogin = true;

    /**
    * @var bool Option to enable/disable the registration and "signup-activation / signup-activation-resend" actions
    */
    public $enableSignup = true;

    /**
    * @var bool Option to force email activation after signup
    */
    public $enableEmailSignupActivation = true;

    /**
    * @var bool Option to force email activation after identity update (username and email)
    */
    public $enableEmailEditActivation = true;

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
     * @param array $config a list of name-value pairs that will be used to initialize the object properties.
     * @return Model|null the model instance, null if the model does not exist
     */
    public function getModel($id, $load = true, array $config = [])
    {
        $id = strtolower($id);

        if (isset($this->_models[$id])) {
            if ($load) {
                Yii::trace("Loading model: $id", __METHOD__);
                if (!is_array($this->_models[$id]) && !isset($this->_models[$id]['class'])) {
                    $this->_models[$id] = ArrayHelper::merge(['class' => $this->_models[$id]], $config);
                }

                return Yii::createObject($this->_models[$id]);
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
            'user' => 'cakebake\accounts\models\User',
            'account_data' => 'cakebake\accounts\models\AccountData',
        ];
    }
}
