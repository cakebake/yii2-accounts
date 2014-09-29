<?php

namespace cakebake\accounts\models;

use Yii;
use yii\helpers\ArrayHelper;
use cakebake\behaviors\ScalableBehavior;

/**
 * This is the model class for table "app_account_data".
 *
 * In future, the model is extended with a dynamic model, which provides custom fields
 *
 * @property string $id
 * @property string $account_id
 * @property string $field_type
 * @property string $field_value
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
    * @var array Virtual attributes definition with all of its rules and settings
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
                'hint' => null,
                'input_options' => [],
                'rules' => [
                    ['about_me', 'string'],
                ]
            ],
            'birthday' => [
                'name' => 'birthday',
                'label' => Yii::t('accounts', $this->getAttributeLabel('birthday')),
                'field_type' => 'date',
                'hint' => Yii::t('accounts', 'The default format is <code>Y-m-d</code>.'),
                'input_options' => [
                    'placeholder' => Yii::t('accounts', 'Your birthday...'),
                    'maxlength' => 10,
                ],
                'rules' => [
                    ['birthday', 'string', 'max' => 10],
                    ['birthday', 'date', 'format' => 'Y-m-d'],
                ]
            ],
            'website' => [
                'name' => 'website',
                'label' => Yii::t('accounts', $this->getAttributeLabel('website')),
                'field_type' => 'url',
                'input_options' => [
                    'placeholder' => Yii::t('accounts', 'http://'),
                    'maxlength' => 100,
                ],
                'rules' => [
                    ['website', 'string', 'max' => 100],
                    ['website', 'url', 'defaultScheme' => 'http'],
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
            [] //static attributes, if there are any to validate
        );
    }
}
