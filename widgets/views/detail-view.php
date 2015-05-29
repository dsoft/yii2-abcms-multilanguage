<?php
use yii\widgets\DetailView;
?>
<?php foreach($languages as $language): ?>
    <h2><?= $language['name'] ?> Translation</h2>
    <?php 
    echo DetailView::widget([
        'model' => $language['model'],
        'attributes'=>$language['attributes'],
    ]);
    ?>
<?php endforeach; ?>
