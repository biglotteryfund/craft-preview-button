<?php

namespace biglotteryfund\previewbutton\models;

use craft\base\Model;

class Settings extends Model
{
    public $urlBase = '';
    public $versionParameter = 'version';
    public $draftParameter = 'draft';

    public function rules()
    {
        return [
            [['urlBase', 'versionParameter', 'draftParameter'], 'required']
        ];
    }
}