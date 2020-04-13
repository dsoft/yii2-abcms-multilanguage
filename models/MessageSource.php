<?php

namespace abcms\multilanguage\models;

use Yii;

/**
 * This is the model class for table "message_source".
 *
 * @property int $id
 * @property string|null $category
 * @property string|null $message
 *
 * @property MessageTranslation[] $translations
 */
class MessageSource extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'message_source';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['message', 'category'], 'required'],
            [['message'], 'string'],
            [['category'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('abcms.multilanguage', 'ID'),
            'category' => Yii::t('abcms.multilanguage', 'Category'),
            'message' => Yii::t('abcms.multilanguage', 'Message'),
        ];
    }

    /**
     * Gets query for [[MessageTranslations]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTranslations()
    {
        return $this->hasMany(MessageTranslation::className(), ['id' => 'id']);
    }
}
