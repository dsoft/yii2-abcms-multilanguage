<?php

namespace abcms\multilanguage;

use Yii;
use yii\base\Component;
use yii\web\Cookie;

/**
 * MultiLanguage Component Class
 */
abstract class MultilanguageBase extends Component
{

    public function init()
    {
        parent::init();
        Yii::$app->on(\yii\web\Controller::EVENT_BEFORE_ACTION, [$this, 'setApplicationLanguage']);
    }

    /**
     * Set the language and save it in cookie if needed.
     * First read it from url if available, second from cookies and last from application language.
     */
    public function setApplicationLanguage()
    {
        $request = Yii::$app->getRequest();
        $language = $request->get('lang');
        // If language is available in the url
        if ($language) {
            // If language code exists in the languages array
            if (key_exists($language, $this->getLanguages())) {
                $cookie = new Cookie([
                    'name' => 'lang',
                    'value' => $language,
                    'expire' => time() + 86400 * 180, // 180 days
                ]);
                $cookies = Yii::$app->getResponse()->getCookies();
                $cookies->add($cookie);
            } else {
                $language = Yii::$app->language;
            }
        } else {
            $cookies = $request->getCookies();
            // If lang cookie already available
            if ($cookies->has('lang')) {
                $language = $cookies->getValue('lang');
            } else {
                $language = Yii::$app->language;
            }
        }
        Yii::$app->language = $language;
    }
    
    /**
     * Translate one model to a specific language
     * @param \yii\db\ActiveRecord $model
     * @param string $lang Language code
     * @return \yii\db\ActiveRecord
     */
    public function translate($model, $lang = NULL)
    {
        if (!$lang) {
            $lang = Yii::$app->language;
        }
        if ($lang == Yii::$app->sourceLanguage) {
            return $model;
        }
        $attributes = $this->translation($model, $lang);
        $copy = clone $model;
        $copy->attributes = $attributes;
        return $copy;
    }

    /**
     * Translate multiple models to a specific language
     * @param \yii\db\ActiveRecord[] $models
     * @param string $lang Language code
     * @param boolean $onlyTranslatedModels Whether to include only translated models or all models
     * @return \yii\db\ActiveRecord[]
     */
    public function translateMultiple($models, $lang = NULL, $onlyTranslatedModels = true)
    {
        if (!$lang) {
            $lang = Yii::$app->language;
        }
        if ($lang == Yii::$app->sourceLanguage) {
            return $models;
        }
        $translation = $this->translationMultiple($models, $lang);
        $array = [];
        foreach ($models as $key => $model) {
            if (isset($translation[$model->id])) {
                $copy = clone $model;
                $copy->attributes = $translation[$model->id];
                $array[] = $copy;
            } elseif (!$onlyTranslatedModels) {
                $copy = clone $model;
                $array[] = $copy;
            }
        }
        return $array;
    }
    
    /**
     * Return the languages array where keys are the language code and values represents the name of the language.
     * @return array Languages array
     */
    abstract public function getLanguages();
    
    /**
     * Return the translated attributes of [[model]] parameter as array with the attribute name as key
     * Only translated attributes will be returned.
     * @param array $models
     * @return array
     */
    abstract public function translation($model, $lang);
    
    /**
     * Return the translated attributes of [[models]] parameter as array with the pk as key
     * Only translated attributes will be returned.
     * @param array $models
     * @param string $lang
     * @return array
     */
    abstract public function translationMultiple($models, $lang);

}
