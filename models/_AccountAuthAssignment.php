<?php

namespace cakebake\accounts\models;

use Yii;

/**
 * This is the model class for table "{{%account_auth_assignment}}".
 *
 * @property string $item_name
 * @property string $user_id
 * @property integer $created_at
 *
 * @property AccountAuthItem $itemName
 */
class AccountAuthAssignment extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%account_auth_assignment}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['item_name', 'user_id'], 'required'],
            [['created_at'], 'integer'],
            [['item_name', 'user_id'], 'string', 'max' => 64]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'item_name' => Yii::t('accounts', 'Item Name'),
            'user_id' => Yii::t('accounts', 'User ID'),
            'created_at' => Yii::t('accounts', 'Created At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getItemName()
    {
        return $this->hasOne(AccountAuthItem::className(), ['name' => 'item_name']);
    }
}
