<?php

use yii\helpers\Html;
use yii\grid\GridView;
use abcms\multilanguage\models\Language;

/* @var $this yii\web\View */
/* @var $searchModel abcms\multilanguage\module\models\LanguageSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('abcms.multilanguage', 'Languages');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="language-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('abcms.multilanguage', 'Create Language'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'name',
            'code',
            [
                'attribute' => 'direction',
                'value' => function($data){
                    return $data->directionName;
                },
                'filter' => Language::getDirectionList(),
            ],
            'ordering',

            ['class' => 'abcms\library\grid\ActivateColumn'],
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
