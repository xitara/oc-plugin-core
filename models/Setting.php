<?php namespace Xitara\Core\Models;

use Model;

/**
 * Config Model
 */
class Setting extends Model
{
    public $implement = ['System.Behaviors.SettingsModel'];
    public $settingsCode = 'xitara_core_setting';
    public $settingsFields = 'fields.yaml';
}
