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
        $attributesArray = [];
        foreach($languages as $code => $language) {
            $fields = $this->createLanguageFields($code);
            /**
             * @var array Array that should be used in the Detail View Widget 'attribtues' property
             */
            $attributes = [];
            foreach($fields as $field) {
                $attributes[] = $field->detailViewAttribute();
            }
            $attributesArray[$code] = $attributes;
        }
        echo $this->render('detail-view', [
            'languages' => $languages,
            'attributesArray'=>$attributesArray,
        ]);
    }

}
