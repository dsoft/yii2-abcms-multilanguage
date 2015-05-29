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
        foreach($languages as $key => $language) {
            $lang = $language['code'];
            $fields = $this->createLanguageFields($lang);
            /**
             * @var array Model that should be used in the Detail View Widget
             */
            $model = [];
            /**
             * @var array Array that should be used in the Detail View Widget 'attribtues' property
             */
            $attributes = [];
            foreach($fields as $field) {
                $model[$field->attribute] = (isset($translations[$lang][$field->attribute])) ? $translations[$lang][$field->attribute] : null;
                $attributes[] = $field->detailViewAttribute();
            }
            $languages[$key]['model'] = $model;
            $languages[$key]['attributes'] = $attributes;
        }
        echo $this->render('detail-view', [
            'languages' => $languages,
        ]);
    }

}
