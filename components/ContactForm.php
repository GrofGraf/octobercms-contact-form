<?php namespace GrofGraf\ContactMe\Components;

use Cms\Classes\ComponentBase;
use GrofGraf\ContactMe\Models\Settings;
use Input;
use Mail;
use Validator;
use ValidationException;

class ContactForm extends ComponentBase
{
    public $rules = [
        'name' => ['required'],
        'email' => ['required', 'email'],
        'message_content' => ['required']
    ];

    public function componentDetails()
    {
        return [
            'name'        => 'contactForm',
            'description' => 'Contact form component'
        ];
    }

    public function defineProperties()
    {
        return [
          'loadJS' => [
            'title' => 'Load JS',
            'description' => 'Load required javascript for animation',
            'type' => 'checkbox',
            'default' => true
          ]
        ];
    }

    public function onRun(){
      if($this->property('loadJS') == true) {
        $this->addJs('assets/js/main.js');
      }
      if($this->enableCaptcha()){
        $this->addJs('https://www.google.com/recaptcha/api.js');
      }
      $this->page['label'] = [
        'name' => Settings::instance()->name_label ?: "Name",
        'email' => Settings::instance()->email_label ?: "Email",
        'attachment' => Settings::instance()->attachment_label ?: "Attachment",
        'message' => Settings::instance()->message_content ?: "Message",
        'captcha' => Settings::instance()->captcha_label ?: "Are you a robot?",
        'button_text' => Settings::instance()->button_text ?: "Send",
        'maillist' => Settings::instance()->maillist_subscribe_label ?: "Add me to maillist"
      ];
    }

    public function onMailSend(){
      if($this->enableCaptcha()){
        $this->rules['g-recaptcha-response'] = ['required'];
      }

      $validator = Validator::make(post(), $this->rules);
      if($validator->fails()){
        throw new ValidationException($validator);
      }
      if($this->enableCaptcha() && !$this->enableCaptcha(post('g-recaptcha-response'))){
        throw new ValidationException(['g-recaptcha-response' => 'Captcha credentials are incorrect']);
      }
      $this->sendMail();
      if(Settings::get('enable_auto_reply')){
        $this->sendAutoReply();
      }
      if($this->mailgunSubscribeExist() && Settings::get('auto_subscribe')){
        if(!Input::has('confirm_subscribe') || post('maillist_subscribe') == 'on'){
          $this->subscribeToMaillist();
        }
      }
      $this->page["contact_confirmation_message"] = Settings::instance()->confirmation_message;
      return;
    }

    public function enableFileUpload(){
      return Settings::get('enable_file_upload');
    }

    public function enableCaptcha(){
      return Settings::get('enable_captcha');
    }

    public function captchaSiteKey(){
      return Settings::get('captcha_site_key');
    }

    public function validateCaptcha($captcha_response){
      $postdata = http_build_query(array(
            'secret'   => Settings::get('captcha_secret_key'),
            'response' => $captcha_response,
            'remoteip' => $_SERVER['REMOTE_ADDR']
          )
        );
        $opts = array('http' =>
            array(
                'method'  => 'POST',
                'header'  => 'Content-type: application/x-www-form-urlencoded',
                'content' => $postdata
            )
        );
        $context  = stream_context_create($opts);
        /* Send request to Googles siteVerify API */
        $response=file_get_contents("https://www.google.com/recaptcha/api/siteverify",false,$context);
        $response = json_decode($response, true);
        return $response['success'];
    }

    public function sendMail(){
      Mail::send('grofgraf.contactme::emails.message', post(), function($m){
        $m->to(Settings::get('email'), Settings::get('name'))
          ->subject('Contact from website')
          ->replyTo(post('email'), post('name'));
        if(Input::file('attachment')){
          $m->attach(Input::file('attachment'));
        }
      });
    }

    public function sendAutoReply(){
      $html = Settings::instance()->auto_reply_content;
      $html = str_replace("{{ name }}", post('name'), $html);
      $html = str_replace("{{name}}", post('name'), $html);
      $html = str_replace("{{ email }}", post('email'), $html);
      $html = str_replace("{{email}}", post('email'), $html);
      $html = str_replace("{{ message_content }}", post('message_content'), $html);
      $html = str_replace("{{message_content}}", post('message_content'), $html);
      Mail::send('grofgraf.contactme::emails.auto-reply', array_merge(post(), array('auto_reply' => $html)), function($m){
        $m->to(post('email'), post('name'))
          ->subject(Settings::instance()->auto_reply_subject);
        if(Settings::get('enable_auto_reply_attachment') && Input::file('attachment')){
          $m->attach(Input::file('attachment'));
        }
      });
    }

    public function mailgunSubscribeExist(){
      return class_exists("\GrofGraf\MailgunSubscribe\Components\SubscribeForm");
    }

    public function subscribeToMaillist(){
      $maillist = Settings::get('maillist_title') ?: null;
      \GrofGraf\MailgunSubscribe\Components\SubscribeForm::subscribe(post('email'), $maillist, post('name'), 0);
    }
}
