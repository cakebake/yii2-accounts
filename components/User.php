<?php

namespace cakebake\accounts\components;

use Yii;
use cakebake\actionlog\model\ActionLog;

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

    /**
    * Get users Nicename
    *
    * @param string|null $default The default value
    */
    public function getNicename($default = null)
    {
        if ($user = $this->getIdentity()) {
            return $user->getNicename($default);
        }

        return $default;
    }

    /**
     * @inheritdoc
     */
    protected function afterLogout($identity)
    {
        ActionLog::add(ActionLog::LOG_STATUS_INFO, null, $identity->id);
    }
}
