<?php

namespace abcms\multilanguage;

interface MultilanguageInterface
{

    /**
     * Return the languages array where keys are the language code and values represents the name of the language.
     * @return array Languages array
     */
    public static function getLanguagesList();

}
