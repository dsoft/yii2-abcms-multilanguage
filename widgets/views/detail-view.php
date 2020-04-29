<?php

use yii\widgets\DetailView;
?>
<?php foreach ($languages as $code => $language): ?>
    <h2><?= $language ?> Translation</h2>
    <?php foreach ($modelsArray as $modelArray):
        $attributesArray = $modelArray['attributesArray'];
        $title = $modelArray['title'];
        ?>
        <?php if($title): ?>
        <h3><?= $title; ?></h3>
        <?php endif; ?>
        <?php
        echo DetailView::widget([
            'model' => [],
            'attributes' => $attributesArray[$code],
        ]);
        ?>
    <?php endforeach; ?>
<?php endforeach; ?>
