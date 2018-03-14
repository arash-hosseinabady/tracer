<?php

namespace app\components\pdp;

use kartik\base\InputWidget;
use kartik\base\AssetBundle;
use yii\helpers\Html;
use yii\web\View;

/**
 * This is just an example.
 */
class PersianDatePicker extends InputWidget
{
    public $id;
    public $name;
    public $value = '';
    public $options = [];
    public $format = 'H:m YYYY-M-D';
    public $onlyTimePicker = 'false';

    public function run()
    {
        if (isset($this->options['value'])) {
            $this->value = $this->options['value'];
        }
        parent::run();
        $this->renderWidget();
    }

    public function renderWidget()
    {
        $this->renderView();
        $this->registerAssetBundle();
        $this->registerJs();
    }

    public function renderView()
    {
        if (empty($this->id)) {
            $this->id = Html::getInputId($this->model, $this->attribute);
        }
        if (empty($this->name)) {
            $this->name = Html::getInputName($this->model, $this->attribute);
        }
        echo Html::tag('input', '', [
            'id' => $this->id,
            'name' => $this->name,
            'class' => 'form-control',
            'type' => 'text',
            'value' => $this->value,
            'placeholder' => isset($this->options['placeholder']) ? $this->options['placeholder'] : '',
        ]);
    }

    public function registerJs()
    {
        $view = $this->getView();
        $enableTimePicker = 'true';
        if (isset($this->options['enableTimePicker'])) {
            if (!$this->options['enableTimePicker']) {
                $enableTimePicker = 'false';
                $this->format = 'YYYY-M-D';
            }
        }

        if (isset($this->options['onlyTimePicker'])) {
            if ($this->options['onlyTimePicker']) {
                $this->onlyTimePicker = 'true';
                $this->format = 'H:m';
            }
        }

        if (isset($this->options['format'])) {
            $this->format = $this->options['format'];
        }

        $js = "$('#" . $this->id . "')" . ".persianDatepicker({
                format: '" . $this->format . "',
                onlyTimePicker: " . $this->onlyTimePicker . ",
                timePicker: {
                    enabled: " . $enableTimePicker . ",
                    scrollEnabled: true,
                    second: {
                        enabled: false,
                    }
                },
                calendar:{
                    persian: {
                        locale: 'en'
                    }
                },
            });
            if (!$('#" . $this->id . "').attr('value') || $('#" . $this->id . "').attr('value') == 0) {
                $('#" . $this->id . "').val('" . $this->value . "');
            }
            ";
        $view->registerJs($js, View::POS_END);
    }

    public function registerAssetBundle()
    {
        $view = $this->getView();
        /**
         * @var AssetBundle $bundleClass
         */
        $bundleClass = __NAMESPACE__ . '\PersianDatePickerAsset';
        $bundleClass::register($view);
    }
}
