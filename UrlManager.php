<?php

namespace abcms\multilanguage;

use Yii;

/**
 * UrlManager extends \yii\web\UrlManager
 * and adds lang param to each created url
 * In configuration change urlManager component class to this className:
 * 'urlManager' => [
            'class'=>  abcms\multilanguage\UrlManager::className(),
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'enableStrictParsing' => false,
            'rules' => [
                '<lang:\w{2}>/<controller>/<id:\d+>/<urlTitle>/' => '<controller>/detail',
                '<lang:\w{2}>/<controller>/<action>/' => '<controller>/<action>',
                '<controller>/<id:\d+>/<urlTitle>/' => '<controller>/detail',
            ],
        ],
 */
class UrlManager extends \yii\web\UrlManager
{
    
    /**
     * @var boolean if true, add the lang param when creating a url
     */
    public $languageInUrl = true;

    /**
     * @inheritdocs
     */
    public function createUrl($params)
    {
        $params = (array) $params;
        if($this->languageInUrl && !isset($params['lang'])) {
            $params['lang'] = Yii::$app->language;
        }
        return parent::createUrl($params);
    }

}
