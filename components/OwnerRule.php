<?php

namespace cakebake\accounts\components;

use Yii;

/**
 * @inheritdoc
 */
class OwnerRule extends \yii\rbac\Rule
{
    /**
     * @inheritdoc
     */
    public $name = 'isOwner';

    /**
     * @inheritdoc
     */
    public function execute($user, $item, $params)
    {
        //DebugBreak();
        //own user account
        if ((int)$params['model']->id === (int)$user &&
            $params['model']::className() == Yii::$app->getModule('accounts')->getModel('user', false)) {

            return true;
        }

        //user is owner of model (detected by property ´created_by´)
        return false;
//        $c = (int)$params['model']->created_by;
//        $u = (int)$user;
//        DebugBreak();
//        return isset($params['model']) ? ((int)$params['model']->created_by === (int)$user) : false;
    }
}
