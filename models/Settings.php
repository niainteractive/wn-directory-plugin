<?php namespace NiaInteractive\Directory\Models;

use Model;

/**
 * Winter 1.3 implements settings as a behaviour on a normal model
 * (System\Behaviors\SettingsModel), not as a SettingModel base class.
 */
class Settings extends Model
{
    public $implement = [
        \System\Behaviors\SettingsModel::class,
    ];

    public $settingsCode = 'niainteractive_directory_settings';

    public $settingsFields = 'fields.yaml';
}
