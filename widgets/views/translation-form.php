<?php
use yii\helpers\Html;
use yii\helpers\Inflector;
?>
<?php 
foreach($languages as $language): 
    $fields = $language['fields'];
?>
    <h2><?= $language['name'] ?> Translation</h2>
    <?php foreach($fields as $field): ?>
        <div class="form-group">
            <?= Html::activeLabel($field->model, $field->attributeExpression, ['label' => Inflector::camel2words($field->attribute)]) ?>
            <?= $field->renderInput() ?>
        </div>
    <?php endforeach; ?>
<?php endforeach; ?>
