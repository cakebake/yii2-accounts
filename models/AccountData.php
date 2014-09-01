<?php

namespace cakebake\accounts\models;

use Yii;
use yii\helpers\ArrayHelper;

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
    * Field Types
    */
    const FIELD_TYPE_STORAGE = 0;
    const FIELD_TYPE_PROFILE = 10;


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%account_data}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            [
                'class' => 'cakebake\accounts\behaviors\DataBehavior',
                'serializedAttributes' => ['field_value'],
                'virtualAttributes' => ['about_me', 'birthday']
            ],
        ];
    }

    /**
    * Account Relational Data
    * @return \yii\db\ActiveQuery
    */
//    public function getAccountDataUser()
//    {
//        $modelPath = Yii::$app->getModule('accounts')->getModel('user', false);
//
//        return $this->hasMany($modelPath::className(), ['id' => 'account_id']);
//    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            //field_value
            //['field_value', 'validateValues'],
            [['about_me', 'birthday'], 'safe'],
//            [['account_id', 'field_type'], 'integer'],
//            [['field_name'], 'required'],
//            [['field_value', 'validation_rules'], 'string'],
//            [['field_name'], 'string', 'max' => 255]
        ];
    }

//    public function validateValues($attribute, $params)
//    {
//        if (!is_array($this->$attribute)) {
//            $this->addError($attribute, 'The data format was not accepted.');
//        }
//    }

}
