<?php

namespace abcms\multilanguage;

use Yii;
use yii\base\Object;

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

}
