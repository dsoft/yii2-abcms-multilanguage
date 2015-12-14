<?php

use yii\helpers\Html;
use yii\helpers\Inflector;
?>
<?php
foreach($languages as $code => $language):
    $fields = $fieldsArray[$code];
    if($fields):
        ?>
        <h2><?= $language ?> Translation</h2>
        <?php foreach($fields as $field): ?>
            <div class="form-group">
                <?= Html::activeLabel($field->model, $field->attributeExpression, ['label' => Inflector::camel2words($field->attribute)]) ?>
                <?= $field->renderInput() ?>
            </div>
        <?php endforeach; ?>
        <?php
    endif;
endforeach;
?>
