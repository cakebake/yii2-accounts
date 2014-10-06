<?php

namespace cakebake\accounts\models;

use Yii;

/**
 * This is the model class for table "{{%account_auth_item_child}}".
 *
 * @property string $parent
 * @property string $child
 */
class AuthItemChild extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%account_auth_item_child}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['parent', 'child'], 'required'],
            [['parent', 'child'], 'string', 'max' => 64]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'parent' => Yii::t('accounts', 'Parent'),
            'child' => Yii::t('accounts', 'Child'),
        ];
    }

//    /**
//     * @return \yii\db\ActiveQuery
//     */
//    public function getParent0()
//    {
//        return $this->hasOne(AccountAuthItem::className(), ['name' => 'parent']);
//    }
//
//    /**
//     * @return \yii\db\ActiveQuery
//     */
//    public function getChild0()
//    {
//        return $this->hasOne(AccountAuthItem::className(), ['name' => 'child']);
//    }
}
