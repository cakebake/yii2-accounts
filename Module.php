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
    * Sets the model paths by model ID
    */
    private function _setModelPaths() {
        $this->_models = [
            'account' => 'cakebake\accounts\models\Account',
            'user' => 'cakebake\accounts\models\User',
            'login' => 'cakebake\accounts\models\LoginForm',
            'forgot_password' => 'cakebake\accounts\models\ForgotPasswordForm',
            'signup' => 'cakebake\accounts\models\SignupForm',
        ];
    }

    /**
    * put your comment there...
    *
    * @param string $id model ID
    * @return string|null the model path, null if the model path does not exist
    */
    /*private function _getModelPath($id) {
        $models = [
            'user' => 'cakebake\accounts\models\User',
        ];

        if (!isset($models[$id]))
            return null;

        return $models[$id];
    }*/
}
