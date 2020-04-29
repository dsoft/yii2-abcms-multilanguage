<?php
foreach($languages as $code => $language):
    ?>
    <h2><?= $language ?> Translation</h2>
    <?php
    foreach($modelsArray as $modelArray):
        $fieldsArray = $modelArray['fieldsArray'];
        $dynamicModel = $modelArray['dynamicModel'];
        $title = $modelArray['title'];
        $fields = $fieldsArray[$code];
        if($fields):
            ?>
            <?php if($title): ?>
                <h3><?= $title ?></h3>
            <?php endif; ?>
            <?php foreach($fields as $field): ?>
                <?= $field->renderActiveField($form->field($dynamicModel, $field->inputName)); ?>
            <?php endforeach; ?>
            <?php
        endif;
    endforeach;
endforeach;
?>
