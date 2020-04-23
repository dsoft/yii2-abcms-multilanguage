<?php

namespace abcms\multilanguage\behaviors;

use Yii;
use abcms\library\models\Model;
use yii\base\InvalidConfigException;
use abcms\library\fields\Text;
use yii\helpers\Inflector;
use abcms\multilanguage\MultilanguageBase;
use abcms\library\fields\Field;
use yii\db\BaseActiveRecord;

class ModelBehavior extends \yii\base\Behavior implements MultilanguageInterface
{

    /**
     * array of attributes that should be translated
     * @var array 
     */
    public $attributes = null;
    
    /**
     * @var string the Multilanguage application component ID.
     */
    public $multilanguageId = 'multilanguage';
    
    /**
     *
     * @var boolean Set to true if you want to automatically validate and save the translation data using insert and update events
     */
    public $automaticTranslationSaving = false;

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();
        if($this->attributes === null) {
            throw new InvalidConfigException('"attributes" property must be set.');
        }
        if(!is_array($this->attributes)) {
            throw new InvalidConfigException('"attributes" property should be an array.');
        }
    }
    
    /**
     * @inheritdocs
     */
    public function events()
    {
        return [
            BaseActiveRecord::EVENT_AFTER_VALIDATE => 'validateTranslation',
            BaseActiveRecord::EVENT_BEFORE_INSERT => 'beforeOwnerSave',
            BaseActiveRecord::EVENT_BEFORE_UPDATE => 'beforeOwnerSave',
            BaseActiveRecord::EVENT_AFTER_INSERT => 'saveTranslation',
            BaseActiveRecord::EVENT_AFTER_UPDATE => 'saveTranslation',
        ];
    }
    

    /**
     * Function called after validating the owner.
     * It validates the translation model
     */
    public function validateTranslation()
    {
        if($this->automaticTranslationSaving){
            $model = $this->getTranslationModel();
            if($model->load(Yii::$app->request->post())){
                $model->isLoaded = true;
                $model->validate();
            }
        }
    }
    
    /**
     * Function called before saving the owner's data.
     * If the translation model has errors, the event will stop the owner insert or update call
     * @param \yii\base\ModelEvent $event
     */
    public function beforeOwnerSave($event)
    {
        $model = $this->getTranslationModel();
        if(!$model->isLoaded && $model->load(Yii::$app->request->post())){
            $model->isLoaded = true;
        }
        if($model->hasErrors())
        {
            $event->isValid = false;
        }
    }
    
    /**
     * Function called in EVENT_AFTER_INSERT and EVENT_AFTER_UPDATE to save the translation data
     */
    public function saveTranslation()
    {
        if($this->automaticTranslationSaving){
            $model = $this->getTranslationModel();
            $this->saveTranslationData($model->attributes);
        }
    }

    /**
     * Return the multilanguage component
     * @return MultilanguageBase
     */
    public function getMultilanguage()
    {
        return Yii::$app->get($this->multilanguageId);
    }

    /**
     * id of the owner model in the model table
     * @var string
     */
    private $_modelId = null;

    /**
     * Return [[_modelId]] and get it from the model table if it's not set.
     * @return string
     */
    public function returnModelId()
    {
        if(!$this->_modelId) {
            /** @var ActiveRecord $owner */
            $owner = $this->owner;
            $this->_modelId = Model::returnModelId($owner->className());
        }
        return $this->_modelId;
    }

    /**
     * Return the attributes that should be translated
     * @return array
     */
    public function getAttributesForTranslation()
    {
        return $this->attributes;
    }

    /**
     * @var array
     * Array of Field objects
     */
    private $_translationFields = null;

    /**
     * Return Field objects array created from the [[attributes]] property
     * key is the attribute name and value is the Field object.
     * @return \abcms\library\fields\Field[]
     */
    public function getTranslationFields()
    {
        if(!$this->_translationFields) {
            $array = [];
            foreach($this->attributes as $attribute) {
                $config = self::normalizeObject($attribute);
                $attributeName = $config['attribute'];
                unset($config['attribute']);
                $field = Yii::createObject($config);
                $array[$attributeName] = $field;
            }
            $this->_translationFields = $array;
        }
        return $this->_translationFields;
    }
    
    /**
     * Return translation fields for a certain language after setting inputName, label, and value
     * @param string $language
     * @return \abcms\library\fields\Field[]
     */
    public function getLanguageFields($language)
    {
        $fields = $this->getTranslationFields();
        $translation = $this->getMultilanguage()->translation($this->owner, $language);
        $array = [];
        foreach ($fields as $attribute => $originalField) {
            $value = isset($translation[$attribute]) ? $translation[$attribute] : null;
            $inputName = $this->getTranslationInputName($attribute, $language);
            $field = clone $originalField;
            $field->inputName = $inputName;
            $field->label = $this->owner->getAttributeLabel($attribute);
            $field->value = $value;
            $array[] = $field;
        }
        return $array;
    }
    
    /**
     * Return the input name of the translation field
     * @param string $attribute
     * @param string $language
     * @return string
     */
    public function getTranslationInputName($attribute, $language)
    {
        return $attribute.'_'.$language;
    }

    /**
     * Receive string or array and transform it to an array that can be used to call Yii::createObject() to create a Field class
     * The attribute must be specified in the format of "attribute", "attribute:type" or as an array.
     * Type in "attribute:type" represents the classname as Id, like: text, text-area...
     * If a field is of class [[TextInput]], the "class" element can be omitted.
     * @param string $attribute
     * @return array
     * @throws InvalidConfigException
     */
    protected static function normalizeObject($attribute)
    {
        if(is_string($attribute)) {
            if(!preg_match('/^([^:]+)(:(.*))?$/', $attribute, $matches)) {
                throw new InvalidConfigException('The attribute must be specified in the format of "attribute", "attribute:type"');
            }
            $class = isset($matches[3]) ? '\abcms\library\fields\\'.Inflector::id2camel($matches[3]) : Text::className();
            $array = [
                'class' => $class,
                'attribute' => $matches[1],
            ];
            return $array;
        }
        if(is_array($attribute)) {
            if(!isset($attribute['class'])) {
                $attribute['class'] = TextInput::className();
            }
            return $attribute;
        }
        throw new InvalidConfigException('The attribute must be specified in the format of "attribute", "attribute:type" or as an array');
    }
    
    /**
     * Return the translated model
     * @param type $lang
     * @return \yii\db\ActiveRecord
     */
    public function translate($lang = NULL)
    {
        $multilanguage = $this->getMultilanguage();
        $owner = $this->owner;
        return $multilanguage->translate($owner, $lang);
    }
    
    protected $_translationModel;
    
    /**
     * Return a DynamicModel with all translation languages fields
     * @return \yii\base\DynamicModel
     */
    public function getTranslationModel()
    {
        if(!$this->_translationModel){
            $multilanguage = $this->getMultilanguage();
            $languages = $multilanguage->getTranslationLanguages();
            $allLanguagesFields = [];
            foreach($languages as $languageCode => $languageName)
            {
                $allLanguagesFields = array_merge($allLanguagesFields, $this->getLanguageFields($languageCode));
            }
            $model = Field::getDynamicModel($allLanguagesFields);
            $model->defineAttribute('isLoaded', false);
            $this->_translationModel = $model;
        }
        
        return $this->_translationModel;        
    }
    
    /**
     * Save translation data
     * @param array $data An array where each key has the following format attributeName_language 
     * and value is the translation that should be saved
     */
    public function saveTranslationData($data)
    {
        $multilanguage = $this->getMultilanguage();
        $multilanguage->saveTranslation($this->owner, $data);
    }

}
