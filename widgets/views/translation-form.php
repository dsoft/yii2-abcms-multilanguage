<?php
foreach($languages as $code => $language):
    $fields = $fieldsArray[$code];
    if($fields):
        ?>
        <h2><?= $language ?> Translation</h2>
        <?php foreach($fields as $field): ?>
            <?= $field->renderActiveField($form->field($dynamicModel, $field->inputName)); ?>
        <?php endforeach; ?>
        <?php
    endif;
endforeach;
?>
