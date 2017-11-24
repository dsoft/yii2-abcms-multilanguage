<?php

namespace abcms\multilanguage;

use Yii;
use yii\base\Object;
use abcms\multilanguage\models\Translation;

/**
 * MultiLanguage Main Class
 */
class Multilanguage extends Object implements MultilanguageInterface
{

    /**
     * @inheritdoc
     */
    public static function getLanguagesList()
    {
        return isset(Yii::$app->params['languages']) ? Yii::$app->params['languages'] : [];
    }

    /**
     * Return translated models
     * Clone the [[models]] objects and replace the translated attributes
     * @param array $models
     * @param string $lang The language that models should be translated to
     * @param boolean $onlyTranslatedModels Whether to include only translated models or all models
     * @return array
     */
    public static function translateMultiple($models, $lang = NULL, $onlyTranslatedModels = true)
    {
        if(!$lang) {
            $lang = Yii::$app->language;
        }
        if($lang == Yii::$app->sourceLanguage) {
            return $models;
        }
        $translation = Translation::translationMultiple($models, $lang);
        $array = [];
        foreach($models as $key => $model) {
            if(isset($translation[$model->id])) {
                $copy = clone $model;
                $copy->attributes = $translation[$model->id];
                $array[] = $copy;
            }
            elseif(!$onlyTranslatedModels) {
                $copy = clone $model;
                $array[] = $copy;
            }
        }
        return $array;
    }

}
