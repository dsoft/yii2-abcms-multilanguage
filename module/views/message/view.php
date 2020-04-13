<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $model abcms\multilanguage\models\MessageSource */

$this->title = $model->message;
$this->params['breadcrumbs'][] = ['label' => Yii::t('abcms.multilanguage', 'Message Sources'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->id;
\yii\web\YiiAsset::register($this);
?>
<div class="message-source-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('abcms.multilanguage', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('abcms.multilanguage', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('abcms.multilanguage', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'category',
            'message:ntext',
        ],
    ]) ?>
    
    <p>
        <?= Html::a(Yii::t('abcms.multilanguage', 'Create Translation'), ['message-translation/create', 'id' => $model->id], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            
            'language',
            'translation:ntext',

            ['class' => 'yii\grid\ActionColumn', 'template' => '{update} {delete}', 'controller' => 'message-translation'],
        ],
    ]); ?>

</div>
