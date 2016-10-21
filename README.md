# Yii2 ABCMS Multi Language 

## Install:
```bash
composer require abcms/yii2-multilanguage
```

## Enable multi language support in your website:

### 1. Add language and sourceLanguage attributes to your config array.
```php
'language' => 'en',
'sourceLanguage' => 'en',
```

### 2. Add languages list to params array in params.php
```php
'languages' => [
    'en' => 'English',
    'ar' => 'Arabic',
],
```

### 3. Add custom url manager:
This url manager class will automatically add the language to each url.
```php
'urlManager' => [
      'class' => abcms\multilanguage\UrlManager::className(),
      'enablePrettyUrl' => true,
      'showScriptName' => false,
      'rules' => [
          '<lang:\w{2}>/<controller>/<action>/' => '<controller>/<action>',
      ],
],
```

### 5. Add language switcher to the layout.
Using language bar widget:
```php
<?= abcms\multilanguage\widgets\LanguageBar::widget() ?>
```
or manually:
```php
<a class="<?= (Yii::$app->language == 'en') ? 'active' : ''; ?>" href="<?= Url::current(['lang' => 'en']) ?>">En</a>
```

## Enable multi language support in your model and crud:

### 1. Migration:
```bash
./yii migrate --migrationPath=@vendor/abcms/yii2-library/migrations
./yii migrate --migrationPath=@vendor/abcms/yii2-multilanguage/migrations
```

### 2. Add model behvarior:
Add the multi language behavior and specify which attributes can be translated and the type for each field. If field type is not specified, text input will be used by default.

```php
[
    'class' => \abcms\multilanguage\behaviors\ModelBehavior::className(),
    'attributes' => [
        'title',
        'description:text-area',
    ],
],
```

### 3. Add translation form in the admin panel:
Add in _form.php
```php
<?= \abcms\multilanguage\widgets\TranslationForm::widget(['model' => $model]) ?>
```

### 4. Add translation detail in the admin panel:
Add in view.php
```php
<?=
\abcms\multilanguage\widgets\TranslationView::widget([
    'model' => $model,
])
?>
```

## How to get translated content?

### Get a single model translation for the current language:
```php
$translatedModel = $model->translate();
```

### Get multiple models translation for the current language:
```php
use abcms\multilanguage\Multilanguage;

$translatedModels = Multilanguage::translateMultiple($models);
```

