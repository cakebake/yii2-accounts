<?php

namespace cakebake\accounts\models;

use Yii;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * This is the default model class for table "account".
 *
 * This code will be reused in other model classes like User, Admin, etc.
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
class Account extends ActiveRecord
{
    /**
    * Predefined values ​​for the user status
    */
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 10;

    /**
    * Predefined values for the user roles
    */
    const ROLE_GUEST = 0;
    const ROLE_USER = 10;
    const ROLE_ADMIN = 20;

    /**
    * @var string The user password for subsequent processing for the attribute $password_hash
    */
    public $password;

    /**
    * @var string To test the correct input of the password in forms
    */
    public $rePassword;

    /**
    * @var string To check the user identity, before changing secure data
    */
    public $oldPassword;

    /**
    * @return array Return the defined status values
    */
    public static function getDefinedStatusArray()
    {
        return [
            self::STATUS_ACTIVE => Yii::t('accounts', 'Active account'),
            self::STATUS_DELETED => Yii::t('accounts', 'Deleted account'),
        ];
    }

    /**
    * @return array Return the defined roles values
    */
    public static function getDefinedRolesArray()
    {
        return [
            self::ROLE_GUEST => Yii::t('accounts', 'No account'),
            self::ROLE_USER => Yii::t('accounts', 'Default account'),
            self::ROLE_ADMIN => Yii::t('accounts', 'Administrative account'),
        ];
    }

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
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('accounts', 'ID'),
            'username' => Yii::t('accounts', 'Username'),
            'email' => Yii::t('accounts', 'Email'),
            'auth_key' => Yii::t('accounts', 'Auth Key'),
            'password' => Yii::t('accounts', 'Password'),
            'rePassword' => Yii::t('accounts', 'Repeat Password'),
            'password_hash' => Yii::t('accounts', 'Password Hash'),
            'password_reset_token' => Yii::t('accounts', 'Password Reset Token'),
            'role' => Yii::t('accounts', 'Role'),
            'status' => Yii::t('accounts', 'Status'),
            'updated_at' => Yii::t('accounts', 'Updated At'),
            'created_at' => Yii::t('accounts', 'Created At'),
        ];
    }
}
