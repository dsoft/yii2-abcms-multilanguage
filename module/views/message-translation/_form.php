<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model abcms\multilanguage\models\MessageTranslation */
/* @var $form yii\widgets\ActiveForm */

$multilanguage = Yii::$app->controller->module->getMultilanguage();

?>

<div class="message-translation-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'language')->dropDownList($multilanguage->getTranslationLanguages()) ?>

    <?= $form->field($model, 'translation')->textarea(['rows' => 6]) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('abcms.multilanguage', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
