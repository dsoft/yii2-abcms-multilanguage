<?php

namespace abcms\multilanguage\behaviors;

use Yii;
use yii\web\Cookie;

class ControllerBehavior extends \yii\base\Behavior
{
    
    /**
     * @inheritdoc
     */
    public function events()
    {
        return [
            \yii\web\Controller::EVENT_BEFORE_ACTION => 'setLanguage',
        ];
    }

    /**
     * Set the language and save in cookie if needed.
     * First read it from url if available, second from cookies and last from application language.
     */
    public function setLanguage()
    {
        $language = Yii::$app->request->get('lang');
        // If language is available in the url
        if($language) {
            // If language code exists in the languages array
            if(key_exists($language, $this->getLanguages())) {
                $cookie = new Cookie([
                    'name' => 'lang',
                    'value' => $language,
                    'expire' => time() + 86400 * 180, // 180 days
                ]);
                $cookies = Yii::$app->getResponse()->getCookies();
                $cookies->add($cookie);
            }
            else {
                $language = Yii::$app->language;
            }
        }
        else {
            $cookies = Yii::$app->getRequest()->getCookies();
            // If lang cookie already available
            if($cookies->has('lang')) {
                $language = $cookies->getValue('lang');
            }
            else {
                $language = Yii::$app->language;
            }
        }
        Yii::$app->language = $language;
    }

    /**
     * Return applications languages array
     * @return array
     */
    public function getLanguages()
    {
        return Yii::$app->multilanguage->getLanguagesList();
    }

}
