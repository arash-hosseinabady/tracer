<?php
/**
 * Created by PhpStorm.
 * User: arash
 * Date: 9/23/17
 * Time: 11:12 AM
 */

namespace app\components\pdp;

use kartik\base\AssetBundle;

class PersianDatePickerAsset extends AssetBundle
{
    public function init()
    {
        $this->setSourcePath(__DIR__ . '/assets');
        $this->setupAssets('css', [
            'css/persian-datepicker',
        ]);
        $this->setupAssets('js', [
            'js/persian-date',
            'js/persian-datepicker',
        ]);
        parent::init();
    }
}