<?php

namespace abcms\multilanguage\behaviors;

use Yii;
use yii\db\BaseActiveRecord;
use abcms\library\models\Model;
use yii\base\InvalidConfigException;
use abcms\multilanguage\models\Translation;
use abcms\library\fields\Text;
use yii\helpers\Inflector;

class ModelBehavior extends \yii\base\Behavior
{

    /**
     * array of attributes that should be translated
     * @var array 
     */
    public $attributes = null;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        if($this->attributes === null) {
            throw new InvalidConfigException('"attributes" property must be set.');
        }
        if(!is_array($this->attributes)) {
            throw new InvalidConfigException('"attributes" property should be an array.');
        }
    }

    /**
     * @inheritdocs
     */
    public function events()
    {
        return [
            BaseActiveRecord::EVENT_AFTER_INSERT => 'saveTranslation',
            BaseActiveRecord::EVENT_AFTER_UPDATE => 'saveTranslation',
        ];
    }

    /**
     * Save translations for each translation attributes from POST
     */
    public function saveTranslation()
    {
        $post = Yii::$app->request->post();
        if(isset($post['Translation'])) {
            /** @var ActiveRecord $owner */
            $owner = $this->owner;
            $fields = $this->getTranslationFields();
            $modelId = $this->returnModelId();
            foreach($fields as $attributeName => $field) {
                if(isset($post['Translation'][$modelId][$attributeName])) {
                    foreach($post['Translation'][$modelId][$attributeName] as $lang => $value) {
                        $field->value = $value;
                        $field->inputName = "Translation[$modelId][$attributeName][$lang]";
                        if($field->validate()) {
                            Translation::commit($modelId, $owner->id, $attributeName, $lang, $field->value);
                        }
                    }
                }
            }
        }
    }

    /**
     * id of the owner model in the model table
     * @var string
     */
    private $_modelId = null;

    /**
     * Return [[_modelId]] and get it from the model table if it's not set.
     * @return string
     */
    public function returnModelId()
    {
        if(!$this->_modelId) {
            /** @var ActiveRecord $owner */
            $owner = $this->owner;
            $this->_modelId = Model::returnModelId($owner->className());
        }
        return $this->_modelId;
    }

    /**
     * Return the attributes that should be translated
     * @return array
     */
    public function getAttributesForTranslation()
    {
        return $this->attributes;
    }

    /**
     * @var array
     * Array of Field objects
     */
    private $_translationFields = null;

    /**
     * Return Field objects array created from the [[attributes]] property
     * key is the attribute name and value is the Field object.
     * @return array[\abcms\library\fields\Field]
     */
    public function getTranslationFields()
    {
        if(!$this->_translationFields) {
            $array = [];
            foreach($this->attributes as $attribute) {
                $config = self::normalizeObject($attribute);
                $attributeName = $config['attribute'];
                unset($config['attribute']);
                $field = Yii::createObject($config);
                $array[$attributeName] = $field;
            }
            $this->_translationFields = $array;
        }
        return $this->_translationFields;
    }

    /**
     * Return the translated attributes as array if language is provided, or as multi dimensional array if not provided.
     * Only translated attributes will be returned.
     * @param string $lang
     * @return array
     */
    public function translation($lang = null)
    {
        $owner = $this->owner;
        $modelId = $this->returnModelId();
        $pk = $owner->id;
        $condition = [
            'modelId' => $modelId,
            'pk' => $pk,
        ];
        if($lang) {
            $condition['lang'] = $lang;
        }
        $models = Translation::find()->andWhere($condition)->all();
        $array = [];
        foreach($models as $model) {
            $array[$model->lang][$model->attribute] = $model->translation;
        }
        return ($lang && isset($array[$lang])) ? $array[$lang] : $array;
    }

    /**
     * Clone the owner and replace the translated attributes
     * @param string $lang
     * @return ActiveRecord
     */
    public function translate($lang = NULL)
    {
        $owner = $this->owner;
        if(!$lang) {
            $lang = Yii::$app->language;
            if($lang == Yii::$app->sourceLanguage) {
                return $owner;
            }
        }
        $attributes = $this->translation($lang);
        $model = clone $owner;
        $model->attributes = $attributes;
        return $model;
    }

    /**
     * Receive string or array and transform it to an array that can be used to call Yii::createObject() to create a Field class
     * The attribute must be specified in the format of "attribute", "attribute:type" or as an array.
     * Type in "attribute:type" represents the classname as Id, like: text, text-area...
     * If a field is of class [[TextInput]], the "class" element can be omitted.
     * @param string $attribute
     * @return array
     * @throws InvalidConfigException
     */
    protected static function normalizeObject($attribute)
    {
        if(is_string($attribute)) {
            if(!preg_match('/^([^:]+)(:(.*))?$/', $attribute, $matches)) {
                throw new InvalidConfigException('The attribute must be specified in the format of "attribute", "attribute:type"');
            }
            $class = isset($matches[3]) ? '\abcms\library\fields\\'.Inflector::id2camel($matches[3]) : Text::className();
            $array = [
                'class' => $class,
                'attribute' => $matches[1],
            ];
            return $array;
        }
        if(is_array($attribute)) {
            if(!isset($attribute['class'])) {
                $attribute['class'] = TextInput::className();
            }
            return $attribute;
        }
        throw new InvalidConfigException('The attribute must be specified in the format of "attribute", "attribute:type" or as an array');
    }

}
