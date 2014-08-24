<?php

namespace cakebake\accounts\models;

use Yii;

/**
 * This is the model class for table "app_account_data".
 *
 * @property string $id
 * @property string $account_id
 * @property string $field_type
 * @property string $field_name
 * @property string $field_value
 * @property string $validation_rules
 */
class AccountData extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%account_data}}';
    }

    /**
    * Account Relational Data
    * @return \yii\db\ActiveQuery
    */
    public function getAccount()
    {
        $modelPath = Yii::$app->getModule('accounts')->getModel('user', false);

        return $this->hasMany($modelPath::className(), ['id' => 'account_id']);
    }

    /**
     * @inheritdoc
     */
    /*public function rules()
    {
        return [
            [['account_id', 'field_type'], 'integer'],
            [['field_name'], 'required'],
            [['field_value', 'validation_rules'], 'string'],
            [['field_name'], 'string', 'max' => 255]
        ];
    }*/

    /**
     * @inheritdoc
     */
    /*public function attributeLabels()
    {
        return [
            'id' => Yii::t('accounts', 'ID'),
            'account_id' => Yii::t('accounts', 'Account ID'),
            'field_type' => Yii::t('accounts', 'Field Type'),
            'field_name' => Yii::t('accounts', 'Field Name'),
            'field_value' => Yii::t('accounts', 'Field Value'),
            'validation_rules' => Yii::t('accounts', 'Validation Rules'),
        ];
    }*/
}
