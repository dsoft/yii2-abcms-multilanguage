<?php

namespace abcms\multilanguage\components;

/**
 * Multi Language Component, add it to the components array in your configuration:
 *       'multilanguage' => [
 *           'class' => 'abcms\multilanguage\components\Multilanguage',
 *           'languages' => [
 *               'en' => 'English',
 *               'ar' => 'Arabic',
 *           ],
 *       ],
 */
class Multilanguage extends MultilanguageAbstract
{

    /**
     * List of langauges where key is the language code and value is the language name:
     * [
     *   'en' => 'English',
     *   'ar' => 'Arabic',
     * ]
     * @var array
     */
    public $languages = [];
    
    /**
     * @inheritdoc
     */
    public function getLanguagesList()
    {
        return $this->languages;
    }

}
