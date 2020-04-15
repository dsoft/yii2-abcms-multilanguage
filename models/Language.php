<?php

namespace abcms\multilanguage\models;

use Yii;

/**
 * This is the model class for table "language".
 *
 * @property int $id
 * @property string $name
 * @property string $code
 * @property string|null $direction
 * @property int $active
 * @property int $ordering
 */
class Language extends \abcms\library\base\BackendActiveRecord
{
    
    public static $enableDeleted = false;
    
    const DIRECTION_LTR = 'ltr';
    const DIRECTION_RTL = 'rtl';
    
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'language';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'code'], 'required'],
            [['active', 'ordering'], 'integer'],
            [['name', 'code', 'direction'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('abcms.multilanguage', 'ID'),
            'name' => Yii::t('abcms.multilanguage', 'Name'),
            'code' => Yii::t('abcms.multilanguage', 'Code'),
            'direction' => Yii::t('abcms.multilanguage', 'Direction'),
            'active' => Yii::t('abcms.multilanguage', 'Active'),
            'ordering' => Yii::t('abcms.multilanguage', 'Ordering'),
        ];
    }
    
    /**
     * Returns the list of directions.
     * Can be used in drop-downs.
     * @return array
     */
    public static function getDirectionList()
    {
        return [
            self::DIRECTION_LTR => Yii::t('abcms.multilanguage', 'Left to right'),
            self::DIRECTION_RTL => Yii::t('abcms.multilanguage', 'Right to left'),
        ];
    }
    
    /**
     * Returns the direction name
     * @return string|null
     */
    public function getDirectionName()
    {
        $array = self::getDirectionList();
        return (isset($array[$this->direction])) ? $array[$this->direction] : null;
    }
}
