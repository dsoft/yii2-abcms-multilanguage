<?php

namespace abcms\multilanguage\module;

use Yii;
use abcms\multilanguage\MultilanguageBase;

/**
 * multilanguage module definition class
 */
class Module extends \yii\base\Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'abcms\multilanguage\module\controllers';
    
    /**
     * {@inheritdoc}
     */
    public $defaultRoute = 'message';
    
    /**
     * @var string the Multilanguage application component ID.
     */
    public $multilanguageId = 'multilanguage';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();

        // custom initialization code goes here
    }
    
    /**
     * Return the multilanguage component
     * @return MultilanguageBase
     */
    public function getMultilanguage()
    {
        return Yii::$app->get($this->multilanguageId);
    }
}
