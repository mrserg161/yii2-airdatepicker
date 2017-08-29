<?php

namespace mrserg161\airdatepicker;

use mrserg161\airdatepicker\DatePickerAsset;
use yii\bootstrap\Html;
use yii\helpers\Json;
use yii\widgets\InputWidget;

/**
 * Class AirDatePicker
 * Manual http://t1m0n.name/air-datepicker/docs/index-ru.html
 * GitHub https://github.com/t1m0n/air-datepicker
 * @package mrserg161\airdatepicker
 * @author Malovichko Sergey <mrSerg161@gmail.com>
 */

class DatePicker extends InputWidget
{
    public $template = '{input}';

    public $options = [
        'class' => 'form-control',
    ];
    public $clientOptions = [];
    public $clientEvents = [];


    public function init()
    {
        parent::init();
        if (!isset($this->options['id'])) {
            $this->options['id'] = $this->hasModel() ? Html::getInputId($this->model, $this->attribute) : $this->getId();
        }
    }

    public function run()
    {
        $asset = DatePickerAsset::register($this->view);
        if (isset($this->clientOptions['language'])) {
            $lang = $this->clientOptions['language'];
            $this->view->registerJsFile($asset->baseUrl . "/js/i18n/datepicker.$lang.js", [
                'depends' => DatePickerAsset::class,
            ]);
        }
        $id = $this->options['id'];
        $options = empty($this->clientOptions) ? '' : Json::encode($this->clientOptions);
        $js = "jQuery('#$id').datepicker($options)";
        preg_match_all("/\[(\d+)\]([a-z_-]+)/i", $this->attribute, $value);
        $_attr = $value[2][0] ?? $this->attribute;
        if ($value = $this->model->$_attr) {
            $this->clientEvents = array_merge($this->clientEvents, ['selectDate' => 'new Date(' . strtotime($value) * 1000 . ')']);
        }
        foreach ($this->clientEvents as $event => $handler) {
            $js .= ".data('datepicker').$event($handler)";
        }
        $this->view->registerJs($js . ';');

        return strtr($this->template, [
            '{input}' => $this->hasModel()
                ? Html::activeTextInput($this->model, $this->attribute, $this->options)
                : Html::textInput($this->name, $this->value, $this->options),
        ]);
    }
}