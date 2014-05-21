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
        ActionLog::add(['status' => $identity::LOG_MESSAGE_SUCCESS], $identity->id);
    }
}
