# Yii2 ABCMS Multi Language 

## Install:
```bash
composer require abcms/yii2-multilanguage:dev-master
```

## Enable multi language support in your website:

### 1. Add language and sourceLanguage attributes to your config array.
```php
'language' => 'en',
'sourceLanguage' => 'en',
```

### 2. Add multilanguage component:
```php
'multilanguage' => [
  'class' => 'abcms\multilanguage\components\Multilanguage',
  'languages' => [
    'en' => 'English',
    'ar' => 'Arabic',
  ],
],
```

### 3. Add multilanguage controller behavior to  your website controllers:
```php
public function behaviors()
{
  return [
    \abcms\multilanguage\behaviors\ControllerBehavior::className(),
  ];
}
```

### 4. Add language switcher to the layout.
Using language bar widget:
```php
<?= abcms\multilanguage\widgets\LanguageBar::widget() ?>
```
or manually:
```php
<a class="<?= (Yii::$app->language == 'en') ? 'active' : ''; ?>" href="<?= Url::current(['lang' => 'en']) ?>">En</a>
```
