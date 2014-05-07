<?php

namespace cakebake\accounts\components;

use Yii;

/**
 * @inheritdoc
 */
class User extends \yii\web\User
{
    /**
     * @inheritdoc
     */
    public $identityClass = 'cakebake\accounts\models\User';

    /**
     * @inheritdoc
     */
    public $enableAutoLogin = true;

    /**
     * @inheritdoc
     */
    public $loginUrl = ['/accounts/user/login'];
}
