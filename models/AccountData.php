<?php

namespace cakebake\accounts\models;

use Yii;
use yii\helpers\ArrayHelper;
use cakebake\behaviors\ScalableBehavior;

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
            'scaleable' => [
                'class' => ScalableBehavior::className(),
                'scalableAttribute' => 'field_value',
                'virtualAttributes' => self::virtualAttributes()
            ],
        ];
    }

    /**
    * Defines the virtual attributes, which can be stored by the behavior
    * @return array The virtual attributes keys
    */
    public function virtualAttributes()
    {
        return ['about_me', 'birthday'];
    }

    /**
    * Defines the validation rules of the virtual attributes
    * @return array The virtual attributes rules
    */
    public function virtualAttributesRules()
    {
        return [
            ['about_me', 'string'],
            ['birthday', 'string', 'max' => 60],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return ArrayHelper::merge(
            self::virtualAttributesRules(),
            [] //static attributes, if there are any to define
        );
    }
}
