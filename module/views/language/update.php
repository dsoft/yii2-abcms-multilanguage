<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model abcms\multilanguage\models\Language */

$this->title = Yii::t('abcms.multilanguage', 'Update Language: {name}', [
    'name' => $model->name,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('abcms.multilanguage', 'Languages'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('abcms.multilanguage', 'Update');
?>
<div class="language-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
