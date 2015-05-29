<?php

namespace abcms\multilanguage\widgets;

use Yii;
use abcms\multilanguage\widgets\WidgetBase;
use abcms\multilanguage\Translation;
use abcms\library\models\Model;
use abcms\library\fields\Field;

class TranslationForm extends WidgetBase
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
            $fields = $this->createLanguageFields($language['code']);
            $languages[$key]['fields'] = $fields;
        }
        echo $this->render('translation-form', [
            'languages' => $languages,
        ]);
    }

}
