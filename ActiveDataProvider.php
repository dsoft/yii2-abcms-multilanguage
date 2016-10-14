<?php

namespace abcms\multilanguage;

class ActiveDataProvider extends \yii\data\ActiveDataProvider
{

    /**
     * @inheritdoc
     */
    protected function prepareModels()
    {
        $models = parent::prepareModels();
        $tModels =  Multilanguage::translateMultiple($models, null, false);
        return $tModels;
    }

}
