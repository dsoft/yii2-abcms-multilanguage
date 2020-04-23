<?php

namespace abcms\multilanguage\behaviors;

/**
 * MultilanguageInterface is used in models or classes that should support multilanguage widgets
 */
interface MultilanguageInterface
{
    
    /**
     * Return translation fields for a certain language
     * @param string $language
     * @return \abcms\library\fields\Field[]
     */
    public function getLanguageFields($language);
    
    /**
     * Return a DynamicModel with all translation languages fields
     * @return \yii\base\DynamicModel
     */
    public function getTranslationModel();
    
    /**
     * Save translation data
     * @param array $data An array where the key is the attribute name
     * and value is the translation that should be saved
     */
    public function saveTranslationData($data);
}
