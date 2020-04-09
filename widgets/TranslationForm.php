<?php

namespace abcms\multilanguage\widgets;

use Yii;
use abcms\multilanguage\widgets\WidgetBase;
use yii\base\InvalidConfigException;

class TranslationForm extends WidgetBase
{
    
    /**
     * ActiveForm that the active fields belongs to
     * @var \yii\widgets\ActiveForm
     */
    public $form;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        if(!$this->form) {
            throw new InvalidConfigException('"form" property must be set.');
        }
    }

    /**
     * Renders the widget.
     */
    public function run()
    {
        $languages = $this->languages;
        /**
         * @var array array containing language code as key and related fields as value
         */
        $fieldsArray = [];
        foreach($languages as $key => $language) {
            $fields = $this->model->getLanguageFields($key);
            $fieldsArray[$key] = $fields;
        }
        $dynamicModel = $this->model->getTranslationModel();
        echo $this->render('translation-form', [
            'languages' => $languages,
            'fieldsArray' => $fieldsArray,
            'dynamicModel' => $dynamicModel,
            'form' => $this->form,
        ]);
    }

}
