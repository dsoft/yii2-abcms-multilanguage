<?php

namespace abcms\multilanguage;

use Yii;
use abcms\multilanguage\models\Language;

/**
 * MultilanguageDb Component extends [[Multilanguage]] and adds the ability to store languages
 * in the database.
 */
class MultilanguageDb extends Multilanguage
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
        if(!$this->_languages){
            $models = Language::find()->andWhere(['active' => 1])->orderBy(['ordering' => SORT_ASC, 'id' => SORT_ASC])->all();
            $array = [];
            foreach($models as $model)
            {
                $array[$model->code] = $model->name;
            }
            $this->_languages = $array;
        }
        return $this->_languages;
    }

}
