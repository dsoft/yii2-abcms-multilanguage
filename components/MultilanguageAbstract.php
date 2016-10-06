<?php

namespace abcms\multilanguage\components;

use yii\base\Component;

abstract class MultilanguageAbstract extends Component
{

    /**
     * Return the languages array where keys are the language code and values represents the name of the language.
     * @return array Languages array
     */
    abstract public function getLanguagesList();

}
