<?php

namespace cakebake\accounts\models;

use Yii;
use \yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * This is the model class for table "account".
 *
 * @property string $id
 * @property string $username
 * @property string $email
 * @property string $auth_key
 * @property string $password_hash
 * @property string $password_reset_token
 * @property integer $role
 * @property integer $status
 * @property string $updated_at
 * @property string $created_at
 */
class Admin extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%account}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => 'yii\behaviors\TimestampBehavior',
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            //username
            ['username', 'required'],
            ['username', 'unique'],
            ['username', 'string', 'min' => 4, 'max' => 255],
            ['username', 'match', 'pattern' => '/^[a-zA-Z0-9_-]+$/'],
            ['username', 'filter', 'filter' => 'trim'],

            //email
            ['email', 'required'],
            ['email', 'unique'],
            ['email', 'email'],
            ['email', 'string', 'min' => 4, 'max' => 255],
            ['email', 'filter', 'filter' => 'trim'],




            //obsolet default rules
            [['username', 'email', 'auth_key', 'password_hash', 'updated_at'], 'required'],
            [['role', 'status'], 'integer'],
            [['username', 'email', 'password_hash', 'password_reset_token'], 'string', 'max' => 255],
            [['auth_key'], 'string', 'max' => 32]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('accounts', 'ID'),
            'username' => Yii::t('accounts', 'Username'),
            'email' => Yii::t('accounts', 'Email'),
            'auth_key' => Yii::t('accounts', 'Auth Key'),
            'password_hash' => Yii::t('accounts', 'Password Hash'),
            'password_reset_token' => Yii::t('accounts', 'Password Reset Token'),
            'role' => Yii::t('accounts', 'Role'),
            'status' => Yii::t('accounts', 'Status'),
            'updated_at' => Yii::t('accounts', 'Updated At'),
            'created_at' => Yii::t('accounts', 'Created At'),
        ];
    }
}
