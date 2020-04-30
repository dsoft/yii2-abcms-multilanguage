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
        if (!$this->form) {
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
         * @var array array containing fields array, dynamic model, and title for each model.
         */
        $modelsArray = [];
        foreach ($this->models as $modelTitle => $model) {
            /**
             * @var array array containing language code as key and related fields as value
             */
            $fieldsArray = [];
            foreach ($languages as $key => $language) {
                $fields = $model->getLanguageFields($key);
                if($fields) {
                    $fieldsArray[$key] = $fields;
                }
            }
            if($fieldsArray) {
                $dynamicModel = $model->getTranslationModel();
                $title = is_string($modelTitle) ? $modelTitle : null;
                $modelsArray[] = [
                    'fieldsArray' => $fieldsArray,
                    'dynamicModel' => $dynamicModel,
                    'title' => $title,
                ];
            }
        }
        echo $this->render('translation-form', [
            'languages' => $languages,
            'modelsArray' => $modelsArray,
            'form' => $this->form,
        ]);
    }

}
