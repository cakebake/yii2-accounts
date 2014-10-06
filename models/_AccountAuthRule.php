<?php

namespace cakebake\accounts\models;

use Yii;

/**
 * This is the model class for table "{{%account_auth_rule}}".
 *
 * @property string $name
 * @property string $data
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property AccountAuthItem[] $accountAuthItems
 */
class AccountAuthRule extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%account_auth_rule}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['data'], 'string'],
            [['created_at', 'updated_at'], 'integer'],
            [['name'], 'string', 'max' => 64]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'name' => Yii::t('accounts', 'Name'),
            'data' => Yii::t('accounts', 'Data'),
            'created_at' => Yii::t('accounts', 'Created At'),
            'updated_at' => Yii::t('accounts', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAccountAuthItems()
    {
        return $this->hasMany(AccountAuthItem::className(), ['rule_name' => 'name']);
    }
}
