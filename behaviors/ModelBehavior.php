<?php

namespace abcms\multilanguage\behaviors;

use Yii;
use yii\db\BaseActiveRecord;
use abcms\library\models\Model;
use yii\base\InvalidConfigException;
use abcms\library\fields\Field;
use abcms\multilanguage\models\Translation;

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
            foreach($fields as $field) {
                $attributeName = $field->attribute;
                if(isset($post['Translation'][$modelId][$attributeName])) {
                    foreach($post['Translation'][$modelId][$attributeName] as $lang => $array) {
                        if(isset($array['translation'])) {
                            $field->value = $array['translation'];
                            $field->model = new Translation;
                            $field->attributeExpression = "[$modelId][$attributeName][$lang]translation";
                            if($field->validate()){
                                Translation::commit($modelId, $owner->id, $attributeName, $lang, $field->value);
                            }
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
     * Return Field objects created from the [[attributes]] property
     * @return array
     */
    public function getTranslationFields()
    {
        if(!$this->_translationFields) {
            $array = [];
            foreach($this->attributes as $attribute) {
                $config = Field::normalizeObject($attribute);
                $field = Yii::createObject($config);
                $array[] = $field;
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

}
