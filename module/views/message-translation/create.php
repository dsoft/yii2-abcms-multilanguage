<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model abcms\multilanguage\models\MessageTranslation */

$this->title = Yii::t('abcms.multilanguage', 'Translate "{name}"', [
    'name' => $source->message,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('abcms.multilanguage', 'Message Sources'), 'url' => ['message/index']];
$this->params['breadcrumbs'][] = ['label' => $source->id, 'url' => ['message/view', 'id' => $source->id]];
$this->params['breadcrumbs'][] = Yii::t('abcms.multilanguage', 'Translate');
?>
<div class="message-translation-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
