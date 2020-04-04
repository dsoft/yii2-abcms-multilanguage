<?php

namespace abcms\multilanguage\widgets;

use Yii;
use yii\base\Widget;
use yii\di\Instance;
use abcms\multilanguage\MultilanguageBase;

class LanguageBar extends Widget
{
    
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
    }

    /**
     * Renders the widget.
     */
    public function run()
    {
        $languages = $this->multilanguage->getLanguages();
        echo $this->render('language-bar', [
            'languages' => $languages,
        ]);
    }

}
