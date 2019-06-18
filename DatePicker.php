<?php

namespace mrserg161\airdatepicker;

use Yii;
use yii\base\InvalidParamException;
use yii\helpers\Html;
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

    public $dateFormat;

    public function init()
    {
        parent::init();
        if (!isset($this->options['id'])) {
            $this->options['id'] = $this->hasModel() ? Html::getInputId($this->model, $this->attribute) : $this->getId();
        }
        if ($this->dateFormat === null) {
            $this->dateFormat = Yii::$app->formatter->datetimeFormat;
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

        // get formatted date value
        if ($this->hasModel()) {
            $value = Html::getAttributeValue($this->model, $this->attribute);
        } else {
            $value = $this->value;
        }
        if ($value !== null && $value !== '') {
            try {
                if (is_int($value)) {
                    $value = Yii::$app->formatter->asDatetime($value, $this->dateFormat);
                }
            } catch (InvalidParamException $e) {
                // ignore exception and keep original value if it is not a valid date
            }
        }

        $id = $this->options['id'];
        $clientOptions = empty($this->clientOptions) ? '' : Json::encode($this->clientOptions);
        $js = "jQuery('#$id').datepicker($clientOptions)";
        if ($value) {
            $this->clientEvents = array_merge($this->clientEvents, ['selectDate' => 'new Date(' . strtotime($value) * 1000 . ')']);
        }
        foreach ($this->clientEvents as $event => $handler) {
            $js .= ".data('datepicker').$event($handler)";
        }
        $this->view->registerJs($js . ';');

        $this->options['value'] = $value;

        return strtr($this->template, [
            '{input}' => $this->hasModel()
                ? Html::activeTextInput($this->model, $this->attribute, $this->options)
                : Html::textInput($this->name, $value, $this->options),
        ]);
    }
}