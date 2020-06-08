<?php namespace Xitara\Core\Models;

use Model;

/**
 * Config Model
 */
class Config extends Model
{
    public $implement = ['System.Behaviors.SettingsModel'];
    public $settingsCode = 'xitara_core_config';
    public $settingsFields = 'fields.yaml';
}
