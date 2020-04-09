<?php

namespace abcms\multilanguage\widgets;

use Yii;
use yii\base\Widget;
use yii\base\InvalidConfigException;
use abcms\multilanguage\MultilanguageBase;
use yii\di\Instance;

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
        if(!$this->model) {
            throw new InvalidConfigException('"model" property must be set.');
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
