<?php

use yii\widgets\DetailView;
?>
<?php foreach($languages as $code => $language): ?>
    <h2><?= $language ?> Translation</h2>
    <?php
    echo DetailView::widget([
        'model' => [],
        'attributes' => $attributesArray[$code],
    ]);
    ?>
<?php endforeach; ?>
