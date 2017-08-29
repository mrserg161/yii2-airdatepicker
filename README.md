Air DatePicker
=============
Renders a lightweight [Air DatePicker](https://github.com/t1m0n/air-datepicker) plugin.

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
composer require --prefer-dist mrserg161/yii2-airdatepicker "*"
```

or add

```
"mrserg161/yii2-airdatepicker": "*"
```

to the require section of your `composer.json` file.

Demo
-----

You can refer detailed documentation and demos for [AirDatePicker](http://t1m0n.name/air-datepicker/docs/) or russian docs [AirDatePicker RU](http://t1m0n.name/air-datepicker/docs/index-ru.html) widgets on usage of the extension.

Usage
-----

Once the extension is installed, simply use it in your code by  :

```php
<?php
use mrserg161\airdatepicker\DatePicker;
?>

<?= $form->field($model, 'date')
    ->widget(
        DatePicker::class, [
            'clientOptions' => [
                'autoClose' => true,
                'timepicker' => true,
            ]
        ]
    )?>
```