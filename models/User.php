<?php

namespace cakebake\accounts\models;

use Yii;
use yii\web\IdentityInterface;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\data\ActiveDataProvider;
use yii\base\NotSupportedException;
use yii\base\Formatter;
use yii\helpers\Security;

/**
 * This is the default model class for table "account" and user identity
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
class User extends ActiveRecord implements IdentityInterface
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
    * @var bool The remember me checkbox value
    */
    public $rememberMe = false;

    /**
    * Cache
    */
    private $_user = null;
    private $_nicename = null;
    private $_statusname = null;
    private $_rolename = null;
    private $_updated = null;
    private $_created = null;

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
    public function rules()
    {
        return [
            //username
            ['username', 'required', 'on' => ['login']],
//            ['username', 'unique'],
            ['username', 'string', 'min' => 4, 'max' => 60, 'on' => ['login']],
//            ['username', 'match', 'pattern' => '/^[a-zA-Z0-9_-]+$/', 'message' => Yii::t('accounts', 'Username must consist of letters, numbers, underscores and dashes only.')],
            ['username', 'filter', 'filter' => 'trim', 'on' => ['login']],

            //email
//            ['email', 'required'],
//            ['email', 'unique'],
//            ['email', 'email'],
//            ['email', 'string', 'min' => 4, 'max' => 60],
//            ['email', 'filter', 'filter' => 'trim'],

            //password
            ['password', 'required', 'on' => ['login']],
            ['password', 'string', 'min' => 6, 'max' => 60, 'on' => ['login']],
            ['password', 'validateLogin', 'on' => ['login']],

            //rePassword
//            ['rePassword', 'required'],
//            ['rePassword', 'string', 'min' => 6, 'max' => 60],
//            ['rePassword', 'compare', 'compareAttribute' => 'password', 'message' => Yii::t('accounts', 'Password must be repeated exactly.')],

            //oldPassword
//            ['oldPassword', 'required'],
//            ['oldPassword', 'string', 'min' => 6, 'max' => 60],
//            ['oldPassword', 'validatePassword', 'message' => Yii::t('accounts', 'The password input was wrong. Please try again.')],

            //status
//            ['status', 'required'],
//            ['status', 'default', 'value' => self::STATUS_ACTIVE],
//            ['status', 'in', 'range' => array_keys(self::getDefinedStatusArray())],

            //role
//            ['role', 'required'],
//            ['role', 'default', 'value' => self::ROLE_GUEST],
//            ['role', 'in', 'range' => array_keys(self::getDefinedRolesArray())],

            //rememberMe
            ['rememberMe', 'boolean', 'when' => function($model) { return Yii::$app->user->enableAutoLogin; }, 'on' => ['login']],
        ];
    }

    /**
    * @inheritdoc
    */
    public function scenarios()
    {
        return [
            'login' => Yii::$app->user->enableAutoLogin ? ['username', 'password', 'rememberMe'] : ['username', 'password'],
        ];
    }

    /**
     * Validates the login form
     */
    public function validateLogin()
    {
        if ($this->hasErrors()) {
            $this->addError('password', Yii::t('accounts', 'Incorrect username or password. Please try again.'));
        }

        if (($user = $this->findUser($this->username)) === null) {
            $this->addError('password', Yii::t('accounts', 'Incorrect username or password. Please try again.'));
        }

        if (!$user->validatePassword($this->password)) {
            $this->addError('password', Yii::t('accounts', 'Incorrect username or password. Please try again.'));
        }
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
    * Search method for User model
    *
    * @param mixed $params
    * @return ActiveDataProvider
    */
    public function search($params)
    {
        $query = self::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['created_at' => SORT_DESC]],
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'role' => $this->role,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'email', $this->email]);

        return $dataProvider;
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
    public static function tableName()
    {
        return '{{%account}}';
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
    * Find user by id, username or email
    *
    * @param string $attr id|username|email
    */
    public function findUser($attr)
    {
        if ($this->_user === null) {
            switch (gettype($attr)) {
                case 'integer':
                    $this->_user = self::findById($attr);
                    break;
                case 'string':
                    if (strpos($attr, '@')) {
                        $this->_user = self::findByEmail($attr);
                    } else {
                        $this->_user = self::findByUsername($attr);
                    }
                    break;
            }

        }

        return $this->_user;
    }

    /**
    * Get user by id, username or email
    * This is the alias method for User::findUser($attr)
    *
    * @param string $attr id|username|email
    */
    public function getUser($attr)
    {
        return $this->findUser($attr);
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return self::findOne(['username' => $username]);
    }

    /**
     * Finds user by email
     *
     * @param string $email
     * @return static|null
     */
    public static function findByEmail($email)
    {
        return self::findOne(['email' => $email]);
    }

    /**
     * Finds user by id
     *
     * @param string $id
     * @return static|null
     */
    public static function findById($id)
    {
        return self::findOne($id);
    }

    /**
     * Finds user by password reset token
     *
     * @param  string      $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        $user = static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);

        if (!$user)
            return null;

        $parts = explode('_', $token);
        if (((int)end($parts) + Yii::$app->getModule('accounts')->passwordResetTokenExpire) < time()) {
            $user->removePasswordResetToken();
            $user->save();

            return null;
        }

        return $user;
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param  string  $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Security::validatePassword($password, $this->password_hash);
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
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