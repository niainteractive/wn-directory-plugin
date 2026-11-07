<?php namespace NiaInteractive\Directory\Models;

use System\Models\SettingModel;

class Settings extends SettingModel
{
    public $settingsCode = 'niainteractive_directory_settings';

    public $settingsFields = 'fields.yaml';
}
