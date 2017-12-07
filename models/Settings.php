<?php namespace GrofGraf\ContactMe\Models;

use Model;

class Settings extends Model
{
    use \October\Rain\Database\Traits\Validation;

    public $implement = [
      'System.Behaviors.SettingsModel',
      '@RainLab.Translate.Behaviors.TranslatableModel'
    ];

    public $rules = [
        'name' => ['required'],
        'email' => ['required', 'email'],
        'message_content' => ['required'],
        'name_label' => ['required'],
        'email_label' => ['required'],
        'message_content' => ['required'],
        'button_text' => ['required'],
        'confirmation_message' => ['required'],
        'attachment_label' => ['required_if:enable_file_upload,1'],
        'captcha_label' => ['required_if:enable_captcha,1'],
        'captcha_site_key' => ['required_if:enable_captcha,1'],
        'captcha_secret_key' => ['required_if:enable_captcha,1'],
        'auto_reply_subject' => ['required_if:enable_auto_reply,1'],
        'auto_reply_content' => ['required_if:enable_auto_reply,1'],
    ];

    public $translatable = [
      'name_label',
      'email_label',
      'attachment_label',
      'message_content',
      'button_text',
      'captcha_label',
      'confirmation_message',
      'auto_reply_subject',
      'auto_reply_content'
    ];

    // A unique code
    public $settingsCode = 'contact_me_settings';

    // Reference to field configuration
    public $settingsFields = 'fields.yaml';

    public function filterFields($fields, $context = null){
      if (class_exists("\GrofGraf\MailgunSubscribe\Components\SubscribeForm")) {
        $fields->auto_subscribe->hidden = false;
      }
    }
}
