<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model abcms\multilanguage\models\MessageSource */

$this->title = Yii::t('abcms.multilanguage', 'Update Message: {name}', [
    'name' => $model->message,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('abcms.multilanguage', 'Message Sources'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('abcms.multilanguage', 'Update');
?>
<div class="message-source-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
