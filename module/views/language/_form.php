<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use abcms\multilanguage\models\Language;

/* @var $this yii\web\View */
/* @var $model abcms\multilanguage\models\Language */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="language-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'direction')->dropDownList(Language::getDirectionList()) ?>

    <?= $form->field($model, 'active')->checkbox() ?>

    <?= $form->field($model, 'ordering')->textInput() ?>
    
    <div class="form-group">
        <?= Html::submitButton(Yii::t('abcms.multilanguage', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
