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
                'virtualAttributes' => $this->virtualAttributes()
            ],
        ];
    }

    /**
    * @var array For internal use
    */
    private $_virtualAttributesDefinition = [];

    /**
    * Defines the virtual attributes, which can be stored by the behavior
    *
    * @return array The virtual attributes keys
    */
    public function virtualAttributesDefinition()
    {
        if (!empty($this->_virtualAttributesDefinition)) {
            return $this->_virtualAttributesDefinition;
        }

        return $this->_virtualAttributesDefinition = [
            'about_me' => [
                'name' => 'about_me',
                'label' => Yii::t('accounts', $this->getAttributeLabel('about_me')),
                'field_type' => 'textarea',
                'default_value' => null,
                'hint' => Yii::t('accounts', 'Write something about you...'),
                'rules' => [
                    ['about_me', 'string'],
                    ['about_me', 'required'],
                ]
            ],
            'birthday' => [
                'name' => 'birthday',
                'label' => Yii::t('accounts', $this->getAttributeLabel('birthday')),
                'field_type' => 'text',
                'default_value' => null,
                'hint' => null,
                'rules' => [
                    ['birthday', 'string', 'max' => 60],
                ]
            ],
        ];
    }

    /**
    * Returns the virtual attributes
    * @return array The virtual attributes keys
    */
    public function virtualAttributes()
    {
        return array_keys($this->virtualAttributesDefinition());
    }

    /**
    * Defines the validation rules of the virtual attributes
    * @return array The virtual attributes rules
    */
    public function virtualAttributesRules()
    {
        $rules = [];
        foreach ($this->virtualAttributesDefinition() as $attribute) {
            $rules = ArrayHelper::merge($rules, $attribute['rules']);
        }

        return $rules;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return ArrayHelper::merge(
            $this->virtualAttributesRules(),
            [] //static attributes, if there are any to define
        );
    }
}
