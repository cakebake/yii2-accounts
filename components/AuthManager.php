<?php

namespace cakebake\accounts\components;

use Yii;

/**
 * @inheritdoc
 */
class AuthManager extends \yii\rbac\DbManager
{
    /**
     * @inheritdoc
     */
    public $itemTable = '{{%account_auth_item}}';

    /**
     * @inheritdoc
     */
    public $itemChildTable = '{{%account_auth_item_child}}';

    /**
     * @inheritdoc
     */
    public $assignmentTable = '{{%account_auth_assignment}}';

    /**
     * @inheritdoc
     */
    public $ruleTable = '{{%account_auth_rule}}';

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->db = Yii::$app->getModule('accounts')->db;
        parent::init();
    }
}
