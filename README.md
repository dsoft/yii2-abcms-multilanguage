# Yii2 ABCMS Multi-Language Component

## Features:
* Add a language bar widget to your website
* Translate models
* Manage languages from database or configuration
* Message translation CRUD

## Install:
```bash
composer require abcms/yii2-library:dev-master
composer require abcms/yii2-multilanguage:dev-master
```

## Enable multi-language support in your website:

### 1. Add language and sourceLanguage attributes to your config array.
```php
$config = [
    ......
    'language' => 'en',
    'sourceLanguage' => 'en',
    ......
];
```

### 2. Add multilanguage component
```php
[
    'components' => [
        ......
        'multilanguage' => [
            'class' => 'abcms\multilanguage\Multilanguage',
            'languages' => [
                'en' => 'English',
                'ar' => 'Arabic',
                'fr' => 'French',
            ],
        ],
    ],
]
```

Add the component to the bootstrap array to allow it to read and set the language from cookies and URL: 
```php
'bootstrap' => ['log', 'multilanguage'],
```

### 3. Add custom URL manager:
This URL manager class will automatically add the language to each URL.
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

### 4. Add a language switcher to the layout.
Using the language bar widget:
```php
<?= abcms\multilanguage\widgets\LanguageBar::widget() ?>
```
or manually:
```php
<a class="<?= (Yii::$app->language == 'en') ? 'active' : ''; ?>" href="<?= Url::current(['lang' => 'en']) ?>">En</a>
```

## Enable translation for your models and CRUDs:

### 1. Migration:
```bash
./yii migrate --migrationPath=@vendor/abcms/yii2-library/migrations
./yii migrate --migrationPath=@vendor/abcms/yii2-multilanguage/migrations
```

> You can use [abcms/yii2-generators](https://github.com/dsoft/yii2-abcms-generators) to generate a custom model and CRUD or continue with the manual steps below.

### 2. Add model behavior:
Add the multi-language behavior and specify which attributes can be translated and the type for each field. If the field type is not specified, text input will be used by default.

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
Add in _form.php:
```php
<?= \abcms\multilanguage\widgets\TranslationForm::widget(['model' => $model, 'form' => $form]) ?>
```

### 4. Add translation detail view in the admin panel:
Add in view.php:
```php
<?=
\abcms\multilanguage\widgets\TranslationView::widget([
    'model' => $model,
])
?>
```

### 5. Enable automatic translation saving in the controller
Add in Controller create and update actions:
```php
$model->automaticTranslationSaving = true;
```

## How to get translated content?

### Get a single model translation for the current language:
```php
$translatedModel = $model->translate();
```

### Get multiple models translation for the current language:
```php
$translatedModels = Yii::$app->multilanguage->translateMultiple($models);
```

