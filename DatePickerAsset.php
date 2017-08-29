<?php

namespace mrserg161\airdatepicker;

use yii\bootstrap\BootstrapAsset;
use yii\web\AssetBundle;
use yii\web\JqueryAsset;

class DatePickerAsset extends AssetBundle
{
    public $sourcePath = '@bower/air-datepicker/dist';

    public $css = [
        'css/datepicker.css',
    ];

    public $js = [
        'js/datepicker.js',
    ];

    public $depends = [
        JqueryAsset::class,
        BootstrapAsset::class,
    ];
}