<?php

namespace abcms\multilanguage\widgets;

use Yii;
use yii\base\Widget;
use yii\base\InvalidConfigException;
use abcms\multilanguage\MultilanguageBase;
use yii\di\Instance;
use yii\db\ActiveRecord;

class WidgetBase extends Widget
{

    /**
     * Model that should be translated
     * @var ActiveRecord
     */
    public $model;
    
    /**
     * Models that should be translated
     * @var ActiveRecord[]
     */
    public $models;

    /**
     * Languages list that should be used for translation/display
     * @var array
     */
    public $languages;
    
    /**
     * @var MultilanguageBase|array|string the Multilanguage object or the Multilanguage application component ID.
     */
    public $multilanguage = 'multilanguage';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        
        $this->multilanguage = Instance::ensure($this->multilanguage, MultilanguageBase::className());
        if(!$this->model && !$this->models) {
            throw new InvalidConfigException('"model" or "models" properties must be set.');
        }
        if($this->model) {
            $this->models = [$this->model];
        }
        if($this->languages) {
            if(!$this->validateLanguages($this->languages)) {
                throw new InvalidConfigException('"languages" property should be an array with "code" and "name" key for each language.');
            }
        }
        else {
            $this->languages = $this->multilanguage->getTranslationLanguages();
        }
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

}
