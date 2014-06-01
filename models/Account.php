<?php

namespace cakebake\accounts\models;

use Yii;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\helpers\Security;
use yii\base\Formatter;

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
    * Cache vars
    */
    protected $_nicename = null;
    protected $_statusname = null;
    protected $_rolename = null;
    protected $_updated = null;
    protected $_created = null;

    /**
    * Predefined IDs ​​for the user status
    *
    * @return array Return the defined status values
    */
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 10;
    const STATUS_INACTIVE = 15;
    const STATUS_BANNED = 20;

    public static function getDefinedStatusArray()
    {
        return [
            self::STATUS_ACTIVE => Yii::t('accounts', 'Active'),
            self::STATUS_INACTIVE => Yii::t('accounts', 'Inactive'),
            self::STATUS_BANNED => Yii::t('accounts', 'Banned'),
            self::STATUS_DELETED => Yii::t('accounts', 'Deleted'),
        ];
    }

    /**
    * Predefined IDs for the user roles
    *
    * @return array Return the defined roles values
    */
    const ROLE_GUEST = 0;
    const ROLE_USER = 10;
    const ROLE_MOD = 15;
    const ROLE_ADMIN = 20;
    const ROLE_SUPERADMIN = 25;

    public static function getDefinedRolesArray()
    {
        return [
            self::ROLE_GUEST => Yii::t('accounts', 'Guest'),
            self::ROLE_USER => Yii::t('accounts', 'User'),
            self::ROLE_MOD => Yii::t('accounts', 'Moderator'),
            self::ROLE_ADMIN => Yii::t('accounts', 'Admin'),
            self::ROLE_SUPERADMIN => Yii::t('accounts', 'Superadmin'),
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
    public function beforeSave($insert)
    {
        if (!parent::beforeSave($insert))
            return false;

        //salts the password
        if (!empty($this->password)) {
            $this->setPassword($this->password);
        }

        return true;
    }

    /**
    * Get the status name by status id
    *
    * @param int|null $id The optional status id
    */
    public function getStatus($id = null)
    {
        if ($id === null && isset($this->status)) {
            $id = $this->status;
        }

        if ($id === null)
            return null;

        $statusArray = $this->getDefinedStatusArray();

        if (!isset($statusArray[$id]))
            return null;

        return $statusArray[$id];
    }

    /**
    * Get the role name by role id
    *
    * @param int|null $id The optional role id
    */
    public function getRole($id = null)
    {
        if ($id === null && isset($this->role)) {
            $id = $this->role;
        }

        if ($id === null)
            return null;

        $rolesArray = $this->getDefinedRolesArray();

        if (!isset($rolesArray[$id]))
            return null;

        return $rolesArray[$id];
    }

    /**
    * Get users Nicename
    *
    * @param string|null $default The default value
    */
    public function getNicename($default = null)
    {
        if ($this->_nicename === null) {
            $attributes = [
                'username',
                'email',
            ];
            foreach ($attributes as $attr) {
                if (is_object($this) && !empty($this->$attr)) {
                    return $this->_nicename = $this->$attr;
                }
            }

            return $this->_nicename = $default;
        }

        return $this->_nicename;
    }

    /**
    * Formats the timestamp as the time interval between created_at time and now in human readable form.
    *
    * @param bool $readable Get created_at as DateTime or readable
    */
    public function getCreatedTime($readable = true)
    {
        if (!$readable) {
            return $this->created_at;
        }

        if ($this->_created !== null) {
            return $this->_created;
        }

        $format = new Formatter;

        return $this->_created = $format->format($this->created_at, 'RelativeTime');
    }

    /**
    * Formats the timestamp as the time interval between updated_at time and now in human readable form.
    *
    * @param bool $readable Get updated_at as DateTime or readable
    */
    public function getUpdatedTime($readable = true)
    {
        if (!$readable) {
            return $this->updated_at;
        }

        if ($this->_updated !== null) {
            return $this->_updated;
        }

        $format = new Formatter;

        return $this->_updated = $format->format($this->updated_at, 'RelativeTime');
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Security::generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Security::generateRandomKey();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Security::generateRandomKey() . '_' . time();
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
