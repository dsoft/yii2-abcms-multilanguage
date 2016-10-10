<?php

namespace abcms\multilanguage;

use Yii;
use yii\base\BootstrapInterface;
use yii\web\Controller;
use abcms\multilanguage\Multilanguage;
use yii\web\Cookie;

class Bootstrap implements BootstrapInterface
{

    public function bootstrap($app)
    {
        $app->on(Controller::EVENT_BEFORE_ACTION, function () {
            $this->setLanguage();
        });
    }

    /**
     * Set the language and save in cookie if needed.
     * First read it from url if available, second from cookies and last from application language.
     */
    public function setLanguage()
    {
        if(!Yii::$app->request->getIsConsoleRequest()) {
            $language = Yii::$app->request->get('lang');
            // If language is available in the url
            if($language) {
                // If language code exists in the languages array
                if(key_exists($language, Multilanguage::getLanguagesList())) {
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
    }
}
