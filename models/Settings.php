<?php namespace GrofGraf\ContactMe\Models;

use Model;

class Settings extends Model
{
    public $implement = [
      'System.Behaviors.SettingsModel',
      '@RainLab.Translate.Behaviors.TranslatableModel'
    ];

    public $translatable = [
      'name',
      'email',
      'attachment',
      'message_content',
      'button_text',
      'captcha',
      'confirmation_message'
    ];

    // A unique code
    public $settingsCode = 'contact_me_settings';

    // Reference to field configuration
    public $settingsFields = 'fields.yaml';
}
