<?php

namespace abcms\multilanguage\widgets;

use Yii;
use yii\base\Widget;
use yii\base\InvalidConfigException;
use abcms\multilanguage\Multilanguage;

class WidgetBase extends Widget
{

    /**
     * Model that should be translated
     * @var \yii\db\ActiveRecord
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
     * Validate an array of languages, it should contain a code as key and a name as value
     * @param array $languages
     * @return boolean
     */
    private function validateLanguages($languages)
    {
        if(is_array($languages)) {
            foreach($languages as $code=>$language) {
                if(!is_string($code)){
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
        if(isset($languages[$default])){
            unset($languages[$default]);
        }
        return $languages;
    }

    /**
     * Return all application languages
     * @return array
     */
    private function getAllApplicationLanguages()
    {
        return Multilanguage::getLanguagesList();
    }

    /**
     * Default language
     * @return string
     */
    private function getDefaultLanguage()
    {
        $lang = Yii::$app->sourceLanguage;
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
            $field->inputName = "Translation[$modelId][$key][$language]";
            $field->label = $model->getAttributeLabel($key);
            $field->value = isset($translation[$key]) ? $translation[$key] : null;
            $fields[$key] = $field;
        }
        return $fields;
    }


    /**
     * Create translation fields
     * @return array[\abcms\library\fields\Field]
     */
    public function returnFields()
    {
        return $this->model->getTranslationFields();
    }

}
