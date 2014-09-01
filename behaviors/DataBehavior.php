<?php

namespace cakebake\accounts\behaviors;

use Yii;
use yii\db\ActiveRecord;
use yii\base\Behavior;
use yii\base\DynamicModel;

class DataBehavior extends Behavior
{
    /**
    * @var array The configured attributes to serialize / unserialize their values
    */
    public $serializedAttributes = [];

    /**
    * @var array The configured virtual attributes
    */
    public $virtualAttributes = [];

    /**
    * @var int The level of compression. Can be given as 0 for no compression up to 9 for maximum compression.
    */
    public $compressionLevel = 0;

    /**
    * @var array The checked attributes
    */
    protected $_serializedAttributes = [];

    /**
    * @inheritdoc
    */
    public function events()
    {
        return [
            ActiveRecord::EVENT_BEFORE_INSERT => 'setData',
            ActiveRecord::EVENT_BEFORE_UPDATE => 'setData',
            ActiveRecord::EVENT_AFTER_FIND => 'getData',
            ActiveRecord::EVENT_AFTER_INSERT => 'getData',
        ];
    }

    /**
    * Converts configured attributes before saving them to database
    *
    * @param mixed $event
    */
    public function setData($event)
    {
        if (($attributes = $this->getSerializedAttributes()) == null)
            return false;

        foreach ($attributes as $name) {
            $temp = [];
            foreach ($this->owner->$name as $aKey => $aVal) {
                $temp[$aKey] = $this->owner->{$aKey};
            }
            if (($val = $this->convert($temp)) !== false) {
                $this->owner->{$name} = $val;
            }
        }
    }


    /**
    * Checks if the defined attributes are unserializeable and unserializes their values
    *
    * @param mixed $event
    */
    public function getData($event)
    {
        if (($attributes = $this->getSerializedAttributes()) == null)
            return false;

        foreach ($attributes as $name) {
            if (($val = $this->unConvert($this->owner->$name)) !== false) {
                $this->owner->$name = $val;
                foreach ($val as $aKey => $aVal) {
                    $this->owner->$aKey = $aVal;
                }
            }
        }
    }

    /**
    * Converts data
    *
    * @param mixed $data
    * @return string|false
    */
    protected function convert($data)
    {
        if (empty($data))
            return false;

        if (($data = @serialize($data)) === false)
            return false;

        if ((int)$this->compressionLevel != 0) {
            $data = (($compressed = @gzcompress($data, (int)$this->compressionLevel)) !== false) ? $compressed : $data;
        }

        return $data;
    }

    /**
    * Unconverts data
    *
    * @param mixed $data
    * @return mixed
    */
    protected function unConvert($data)
    {
        if (empty($data))
            return false;

        if (($data = @unserialize($data)) === false)
            return false;

        if ((int)$this->compressionLevel != 0) {
            $data = (($uncompressed = @gzuncompress($data, (int)$this->compressionLevel)) !== false) ? $uncompressed : $data;
        }

        return $data;
    }

    /**
    * Checks behavior configuration
    * @return mixed
    */
    protected function getSerializedAttributes()
    {
        if (!empty($this->_serializedAttributes))
            return $this->_serializedAttributes;

        if (!is_array($this->serializedAttributes) || empty($this->serializedAttributes))
            return null;

        foreach ($this->serializedAttributes as $attribute) {
            if (isset($this->owner->$attribute) && !in_array($attribute, $this->_serializedAttributes)) {
                $this->_serializedAttributes[] = $attribute;
            }
        }

        return !empty($this->_serializedAttributes) ? $this->_serializedAttributes : null;
    }

    /**
    * @inheritdoc
    */
    public function __set($name, $value)
    {
        if (in_array($name, $this->virtualAttributes)) {
            $this->owner->$name = $value;
        } else {
            parent::__set($name, $value);
        }
    }

    /**
    * @inheritdoc
    */
    public function canSetProperty($name, $checkVars = true)
    {
        return in_array($name, $this->virtualAttributes) ? true : parent::canSetProperty($name, $checkVars);
    }

}
