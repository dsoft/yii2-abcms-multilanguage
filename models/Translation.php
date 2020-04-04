<?php

namespace abcms\multilanguage\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "translation".
 *
 * @property integer $id
 * @property integer $modelId
 * @property integer $pk
 * @property string $attribute
 * @property string $lang
 * @property string $translation
 */
class Translation extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'translation';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['modelId', 'pk', 'attribute', 'lang'], 'required'],
            [['modelId', 'pk'], 'integer'],
            [['translation'], 'string'],
            [['attribute'], 'string', 'max' => 255],
            [['lang'], 'string', 'max' => 40]
        ];
    }

    /**
     * Add translation for certain model's attribute, if already exists update it
     * @param int $modelId id of the classname in the model table
     * @param int $pk primary key of model
     * @param string $attribute attribute name of the model
     * @param string $lang
     * @param string $translation
     * @return boolean
     */
    public static function commit($modelId, $pk, $attribute, $lang, $translation)
    {
        $result = false;
        $model = self::returnOne($modelId, $pk, $attribute, $lang);
        if(!$model) { //create new model
            if($translation) { // We shouldn't create a new model if the translation is empty
                $result = self::create($modelId, $pk, $attribute, $lang, $translation);
            }
        }
        else { //update model
            $model->translation = $translation;
            $result = $model->save(false);
        }
        return $result;
    }

    /**
     * Create and save new Translation model
     * @param int $modelId id of the classname in the model table
     * @param int $pk primary key of model
     * @param string $attribute attribute name of the model
     * @param string $lang
     * @param string $translation
     * @return boolean
     */
    public static function create($modelId, $pk, $attribute, $lang, $translation)
    {
        $model = new self;
        $model->modelId = $modelId;
        $model->pk = $pk;
        $model->attribute = $attribute;
        $model->lang = $lang;
        $model->translation = $translation;
        $result = $model->save(false);
        return $result;
    }

    /**
     * return one model by attributes
     * @param int $modelId
     * @param int $pk
     * @param string $attribute
     * @param lang $lang
     * @return Translation
     */
    public static function returnOne($modelId, $pk, $attribute, $lang)
    {
        $model = self::find()->andWhere([
                    'modelId' => $modelId,
                    'pk' => $pk,
                    'attribute' => $attribute,
                    'lang' => $lang,
                ])->one();
        return $model;
    }

}
