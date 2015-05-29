<?php
use yii\helpers\Url;
?>
<?php foreach($languages as $key=>$language): ?><a href="<?= Url::current(['lang'=>$key]) ?>" class="<?= $key ?> <?= ($key == Yii::$app->language) ? 'active' : ''; ?>"><?= strtoupper($key); ?></a><?php endforeach; ?>