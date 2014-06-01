<?php

namespace cakebake\accounts\models;

use Yii;
use yii\helpers\ArrayHelper;

use cakebake\accounts\models\Account;

/**
 * This is the model class for admin actions of this module
 *
 * @inheritdoc
 */
class Admin extends Account
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            //username
            ['username', 'required'],
            ['username', 'unique'],
            ['username', 'string', 'min' => 4, 'max' => 60],
            ['username', 'match', 'pattern' => '/^[a-zA-Z0-9_-]+$/', 'message' => Yii::t('accounts', 'Username must consist of letters, numbers, underscores and dashes only.')],
            ['username', 'filter', 'filter' => 'trim'],

            //email
            ['email', 'required'],
            ['email', 'unique'],
            ['email', 'email'],
            ['email', 'string', 'min' => 4, 'max' => 60],
            ['email', 'filter', 'filter' => 'trim'],

            //password
            ['password', 'required'],
            ['password', 'string', 'min' => 6, 'max' => 60],

            //rePassword
            ['rePassword', 'required'],
            ['rePassword', 'string', 'min' => 6, 'max' => 60],
            ['rePassword', 'compare', 'compareAttribute' => 'password', 'message' => Yii::t('accounts', 'Password must be repeated exactly.')],

            //obsolet default rules
            [['role', 'status'], 'integer'],
            [['password_hash'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return ArrayHelper::merge(
            parent::behaviors(),
            [] //empty for now
        );
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return ArrayHelper::merge(
            parent::attributeLabels(),
            [] //empty for now
        );
    }
}
