<?php

namespace biglotteryfund\previewbutton\models;

use Craft;
use craft\base\Model;

class Settings extends Model
{
    public $urlBase = '';
    public $versionParameter = 'version';
    public $draftParameter = 'draft';

    public function getUrlBase(): string
    {
        return Craft::parseEnv($this->urlBase);
    }

    public function getVersionParameter(): string
    {
        return Craft::parseEnv($this->versionParameter);
    }

    public function getDraftParameter(): string
    {
        return Craft::parseEnv($this->draftParameter);
    }

    public function rules()
    {
        return [
            [['urlBase', 'versionParameter', 'draftParameter'], 'required']
        ];
    }
}