<?php

namespace cakebake\accounts\models;

use Yii;
use yii\base\NotSupportedException;
use yii\helpers\Security;
use yii\web\IdentityInterface;

use cakebake\accounts\models\Account;

/**
 * This is the model class for user actions and user identity of this module
 *
 * @inheritdoc
 */
class User extends Account implements IdentityInterface
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'email', 'role', 'status'], 'required'],

            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DELETED]],

            ['role', 'default', 'value' => self::ROLE_GUEST],
            ['role', 'in', 'range' => [self::ROLE_ADMIN, self::ROLE_USER, self::ROLE_GUEST]],

            [['role', 'status'], 'integer'],
            [['username', 'email'], 'string', 'max' => 255],
            ['email', 'email'],
        ];
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
     * Finds user by username
     *
     * @param  string      $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
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
    * Default user settings on signup
    */
//    public function setSignupDefaults() {
//        $this->status = self::STATUS_ACTIVE;
//        $this->role = self::ROLE_USER;
//    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }
}