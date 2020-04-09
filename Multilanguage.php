<?php

namespace abcms\multilanguage;

use Yii;
use abcms\multilanguage\models\Translation;
use yii\helpers\ArrayHelper;

/**
 * MultiLanguage Component Class
 */
class Multilanguage extends MultilanguageBase
{

    /**
     * @var array Key is the language code and value is the name
     */
    protected $_languages = [];

    /**
     * {@inheritdoc}
     */
    public function getLanguages()
    {
        return $this->_languages;
    }

    /**
     * Set the languages attribute
     * @param array $languages
     */
    public function setLanguages($languages)
    {
        $this->_languages = $languages;
    }
    
    /**
     * {@inheritdoc}
     */
    public function translation($model, $lang)
    {
        $modelId = $model->returnModelId();
        $pk = $model->id;
        $condition = [
            'modelId' => $modelId,
            'pk' => $pk,
            'lang' => $lang,
        ];
        $models = Translation::find()->andWhere($condition)->all();
        $array = [];
        foreach($models as $model) {
            $array[$model->attribute] = $model->translation;
        }
        return $array;
    }
    
    /**
     * {@inheritdoc}
     */
    public function translationMultiple($models, $lang)
    {
        if(isset($models[0])) {
            $modelId = $models[0]->returnModelId();
        }
        else {
            return $models;
        }
        $ids = ArrayHelper::getColumn($models, 'id');
        $condition = [
            'modelId' => $modelId,
            'lang' => $lang,
            'pk' => $ids,
        ];
        $translations = Translation::find()->andWhere($condition)->all();
        $array = [];
        foreach($translations as $translation) {
            $array[$translation->pk][$translation->attribute] = $translation->translation;
        }
        return $array;
    }
    
    /**
     * {@inheritdoc}
     */
    public function saveTranslation($model, $data)
    {
        $languages = $this->getTranslationLanguages();
        $fields = $model->getTranslationFields();
        $modelId = $model->returnModelId();
        $pk = $model->id;
        foreach($fields as $attribute => $field)
        {
            foreach($languages as $lang => $langName)
            {
                $inputName = $model->getTranslationInputName($attribute, $lang);
                if(isset($data[$inputName])){
                    Translation::commit($modelId, $pk, $attribute, $lang, $data[$inputName]);
                }
            }
        }
    }

}
