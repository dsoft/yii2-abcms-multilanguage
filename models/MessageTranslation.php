<?php

namespace abcms\multilanguage\models;

use Yii;

/**
 * This is the model class for table "message_translation".
 *
 * @property int $id
 * @property string $language
 * @property string|null $translation
 *
 * @property MessageSource $source
 */
class MessageTranslation extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'message_translation';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'language'], 'required'],
            [['id'], 'integer'],
            [['translation'], 'string'],
            [['language'], 'string', 'max' => 16],
            [['id', 'language'], 'unique', 'targetAttribute' => ['id', 'language']],
            [['id'], 'exist', 'skipOnError' => true, 'targetClass' => MessageSource::className(), 'targetAttribute' => ['id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('abcms.multilanguage', 'ID'),
            'language' => Yii::t('abcms.multilanguage', 'Language'),
            'translation' => Yii::t('abcms.multilanguage', 'Translation'),
        ];
    }

    /**
     * Gets query for [[Source]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSource()
    {
        return $this->hasOne(MessageSource::className(), ['id' => 'id']);
    }
}
