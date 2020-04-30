<?php

namespace abcms\multilanguage\widgets;

use Yii;
use abcms\multilanguage\widgets\WidgetBase;

class TranslationView extends WidgetBase
{

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
    }

    /**
     * Renders the widget.
     */
    public function run()
    {
        $languages = $this->languages;
        $modelsArray = [];
        foreach($this->models as $modelTitle => $model) {
            $attributesArray = [];
            foreach($languages as $code => $language) {
                $fields = $model->getLanguageFields($code);
                if($fields) {
                    /**
                     * @var array Array that should be used in the Detail View Widget 'attribtues' property
                     */
                    $attributes = [];
                    foreach ($fields as $field) {
                        $attributes[] = $field->getDetailViewAttribute();
                    }
                    $attributesArray[$code] = $attributes;
                }
            }
            
            if($attributesArray) {
                $modelsArray[] = [
                    'attributesArray' => $attributesArray,
                    'title' => is_string($modelTitle) ? $modelTitle : null,
                ];
            }
        }
        
        echo $this->render('detail-view', [
            'languages' => $languages,
            'modelsArray'=> $modelsArray,
        ]);
    }

}
