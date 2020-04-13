<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model abcms\multilanguage\models\MessageSource */

$this->title = Yii::t('abcms.multilanguage', 'Create Message Source');
$this->params['breadcrumbs'][] = ['label' => Yii::t('abcms.multilanguage', 'Message Sources'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="message-source-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
