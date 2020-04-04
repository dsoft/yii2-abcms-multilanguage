<?php

namespace abcms\multilanguage;
use yii\di\Instance;
use abcms\multilanguage\MultilanguageBase;

class ActiveDataProvider extends \yii\data\ActiveDataProvider
{
    
    /**
     * @var MultilanguageBase|array|string the Multilanguage object or the Multilanguage application component ID.
     */
    public $multilanguage = 'multilanguage';
    
    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();
        $this->multilanguage = Instance::ensure($this->multilanguage, MultilanguageBase::className());
    }

    /**
     * {@inheritdoc}
     */
    protected function prepareModels()
    {
        $models = parent::prepareModels();
        $tModels =  $this->multilanguage->translateMultiple($models, null, false);
        return $tModels;
    }

}
