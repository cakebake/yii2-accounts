<?php

namespace cakebake\accounts\models;

use Yii;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\helpers\Inflector;
use yii\web\BadRequestHttpException;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%account_auth_item}}".
 *
 * @property string $name
 * @property integer $type
 * @property string $description
 * @property string $rule_name
 * @property string $data
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property AccountAuthAssignment[] $accountAuthAssignments
 * @property AccountAuthRule $ruleName
 * @property AccountAuthItemChild[] $accountAuthItemChildren
 */
class AccountAuthItem extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%account_auth_item}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            //name
            ['name', 'required', 'on' => ['create', 'update']],
            ['name', 'unique', 'on' => ['create', 'update']],
            ['name', 'string', 'min' => 2, 'max' => 64, 'on' => ['create', 'update']],
            ['name', 'string', 'on' => ['search']],
            ['name', 'match', 'pattern' => '/^[a-zA-Z0-9_-]+$/', 'on' => ['create', 'update'], 'message' => Yii::t('accounts', 'Name may only consist of letters, numbers, underscores and dashes.')],
            ['name', 'filter', 'filter' => 'trim', 'on' => ['create', 'update', 'search']],

            //description
            ['description', 'string', 'on' => ['search', 'create', 'update']],
        ];
    }

    /**
    * @inheritdoc
    */
    public function scenarios()
    {
        return [
            'search' => ['name', 'description'],
            'create' => ['name', 'description'],
            'update' => ['name', 'description'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * @inheritdoc
     */
    public function afterFind()
    {
        parent::afterFind();

        if (empty($this->description)) {
            $this->description = Inflector::camel2words($this->name, true);
        }
    }

    /**
    * Define AccountAuthItem types
    */
    const TYPE_ROLE = 1;
    const TYPE_PERMISSION = 2;

    public function getTypeDefinition()
    {
        return [
            self::TYPE_ROLE => Yii::t('accounts', 'Role'),
            self::TYPE_PERMISSION => Yii::t('accounts', 'Permission'),
        ];
    }

    /**
    * Get type title
    *
    * @param null|string|integer $type The auth item type
    * @param boolean $pluralize
    */
    public function getTypeTitle($type = null, $pluralize = false)
    {
        $typeTitle = $this->typeDefinition[($type === null) ? $this->type : $type];

        return ($pluralize === true) ? Inflector::pluralize($typeTitle) : $typeTitle;
    }

    /**
    * @return HTML Link to model view
    */
    public function getItemLink()
    {
        return Html::a($this->name, ['view', 'id' => $this->name]);
    }

    /**
    * @param string|integer $type The auth item type
    * @return All models by $type
    */
    public static function findByType($type)
    {
        if (!array_key_exists($type, self::getTypeDefinition())) {
            throw new BadRequestHttpException('The requested auth type does not exist.');
        }

        return self::find()->where(['type' => $type]);
    }

    /**
     * Creates data provider instance with search query applied for roles
     *
     * @param array $params
     * @param string|integer $type The auth item type
     * @return ActiveDataProvider
     */
    public function search($params, $type)
    {
        $query = self::findByType($type);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'description', $this->description]);

        return $dataProvider;
    }

    /**
    * Set property ´type´
    *
    * @param string|integer $type The auth item type
    * @return integer
    */
    public function setAuthType($type)
    {
        return $this->type = $type;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAccountAuthAssignments()
    {
        return $this->hasMany(AccountAuthAssignment::className(), ['item_name' => 'name']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRuleName()
    {
        return $this->hasOne(AccountAuthRule::className(), ['name' => 'rule_name']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAccountAuthItemChildren()
    {
        return $this->hasMany(AccountAuthItemChild::className(), ['child' => 'name']);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'name' => Yii::t('accounts', 'Name'),
            'type' => Yii::t('accounts', 'RBAC Type'),
            'typeTitle' => Yii::t('accounts', 'RBAC Type'),
            'description' => Yii::t('accounts', 'Description'),
            'rule_name' => Yii::t('accounts', 'Rule Name'),
            'data' => Yii::t('accounts', 'Data'),
            'created_at' => Yii::t('accounts', 'Created'),
            'updated_at' => Yii::t('accounts', 'Updated'),
            'nicename' => Yii::t('accounts', 'Name'),
        ];
    }
}
