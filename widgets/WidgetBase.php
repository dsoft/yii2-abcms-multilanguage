<?php

namespace abcms\multilanguage\widgets;

use Yii;
use yii\base\Widget;
use yii\base\InvalidConfigException;
use abcms\library\fields\Field;
use abcms\multilanguage\Translation;

class WidgetBase extends Widget
{

    /**
     * array of attributes that should be translated/displayed
     * @var array 
     */
    public $attributes;

    /**
     * Model that should be translated
     * @var ActiveRecord
     */
    public $model;

    /**
     * Languages list that should be used for translation/display
     * @var array
     */
    public $languages;

    /**
     * @inheritdoc
     */
    public function init()
    {
        if(!$this->model) {
            throw new InvalidConfigException('"model" property must be set.');
        }
        if(!isset($this->model->attributesForTranslation)) {
            throw new InvalidConfigException('"model" should implement the multi language behavior: '.\abcms\multilanguage\ModelBehavior::className());
        }
        if(!$this->attributes) {
            $this->attributes = $this->model->attributesForTranslation;
        }
        elseif(!is_array($this->attributes)) {
            throw new InvalidConfigException('"attributes" property should be an array.');
        }
        if($this->languages) {
            if(!$this->validateLanguages($this->languages)) {
                throw new InvalidConfigException('"languages" property should be an array with "code" and "name" key for each language.');
            }
        }
        else {
            $this->languages = $this->getAllTranslationLanguages();
        }
        parent::init();
    }

    /**
     * Validate an array of languages, it should contain a code and a name
     * @param array $languages
     * @return boolean
     */
    private function validateLanguages($languages)
    {
        if(is_array($languages)) {
            foreach($languages as $language) {
                if(!isset($language['code']) || !isset($language['name'])) {
                    return false;
                }
            }
        }
        else {
            return false;
        }
        return true;
    }

    /**
     * Return all languages of the application except for the default language
     * @return array
     */
    private function getAllTranslationLanguages()
    {
        $languages = $this->getAllApplicationLanguages();
        $default = $this->getDefaultLanguage();
        $array = [];
        foreach($languages as $language) {
            if($language['code'] != $default) {
                $array[] = $language;
            }
        }
        return $array;
    }

    /**
     * Return all application languages
     * @return array
     */
    private function getAllApplicationLanguages()
    {
        $array = [
            ['name' => 'English', 'code' => 'en'],
            ['name' => 'Arabic', 'code' => 'ar'],
        ];
        return $array;
    }

    /**
     * Default language
     * @return string
     */
    private function getDefaultLanguage()
    {
        $lang = 'en';
        return $lang;
    }

    /**
     * return the Field objects related to a certain language
     * @param string $language
     * @return array
     */
    public function createLanguageFields($language)
    {
        $model = $this->model;
        $modelId = $model->returnModelId();
        $translation = $model->translation($language);
        $fields = $this->returnFields();
        foreach($fields as $key => $originalField) {
            $field = clone $originalField;
            $m = new Translation;
            if(isset($translation[$field->attribute])) {
                $m->translation = $translation[$field->attribute];
            }
            $field->model = $m;
            $field->value = $m->translation;
            $field->attributeExpression = "[$modelId][{$field->attribute}][$language]translation";
            $fields[$key] = $field;
        }
        return $fields;
    }

    /**
     * @var array
     * attribute where Field objects will be saved in [[returnFields()]]
     */
    private $_fields = null;

    /**
     * Create Field objects from [[attributes]] and return it once
     * @return array
     */
    public function returnFields()
    {
        if(!$this->_fields) {
            $attributes = $this->attributes;
            $fields = [];
            foreach($attributes as $attribute) {
                $config = Field::normalizeObject($attribute);
                $field = Yii::createObject($config);
                $fields[] = $field;
            }
            $this->_fields = $fields;
        }
        return $this->_fields;
    }

}
