<?php

namespace abcms\multilanguage\widgets;

use Yii;
use yii\base\Widget;
use abcms\multilanguage\Multilanguage;

class LanguageBar extends Widget
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
        $languages = $this->getLanguages();
        echo $this->render('language-bar', [
            'languages' => $languages,
        ]);
    }

    /**
     * Return applications languages array
     * @return array
     */
    public function getLanguages()
    {
        return Multilanguage::getLanguagesList();
    }

}
