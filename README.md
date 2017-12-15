# Contact Me
Lightweight front-end contact form plugin. Inspired by another OctoberCMS plugin.

It was made mainly for personal use, because the other plugin doesn't always suits my needs.

You are always welcome to use it and feel free to give any suggestions or opinions about the plugin.

## Advantages
* Translatable content
* File upload for email attachments
* Automatic reply option

## Requirements
* [Ajax Framework](https://octobercms.com/docs/cms/ajax) must be included in your layout/page in order to handle form requests.
* Configure your [mail](https://octobercms.com/docs/services/mail) settings to make sure your server can send emails.

## Optional
* [Translate](https://octobercms.com/plugin/rainlab-translate) plugin, if you want to include multilingual contents.
* [MailgunSubscribe](https://octobercms.com/plugin/grofgraf-mailgunsubscribe), if you want to enable automatic subscribtion to maillist.
* Setup [reCaptcha](https://www.google.com/recaptcha/admin) in case you want to enable human verification test and protect your website from spam and abuse.

## Settings
This plugin creates a Settings menu item, found by navigating to **Settings > Marketing > Contact Form**. This page allows the setting of captcha validation, confirmation message, input labels, button text or enabling file upload for attachments and auto-reply.

If [Translate](https://octobercms.com/plugin/rainlab-translate) is enabled, auto-reply email, button text and labels are translatable.

## Usage
You can put the contact form on any front-end page. Add the `contactForm` component to a page or layout.

The simplest way to add the contact form is to use the component's default partial and the `{% component %}` tag. Add it to a page or layout where you want to display the form:

    {% component 'contactForm' %}

If the default partial is not suitable for your website, replace the component tag with custom code, for example:

    <div class="confirm-contact-container">
    </div>
    <form id="contact-form"
      data-request="{{ __SELF__ }}::onMailSend"
      data-request-update="'{{ __SELF__ }}::confirm': '.confirm-contact-container'"
      {% if __SELF__.enableFileUpload %}
      data-request-files
      {% endif %}
      >
      <div class="form-group">
        <label for="name">
          {{label.name}}
        </label>
        <input type="text" name="name" class="form-control">
      </div>
      <div class="form-group">
        <label for="email">
          {{label.email}}
        </label>
        <input type="text" name="email" class="form-control">
      </div>
      {% if __SELF__.enableFileUpload %}
      <div class="form-group">
        <label for="attachment">{{label.attachment}}</label>
        <input type="file" name="attachment" class="form-control">
      </div>
      {% endif %}
      <div class="form-group">
        <label for="message">
          {{label.message}}
        </label>
        <textarea rows="5" name="message_content" class="form-control"></textarea>
      </div>
      {% if __SELF__.enableCaptcha %}
        <div class="form-group">
          <label for="g-recaptcha">
            {{label.captcha}}
          </label>
          <div class="g-recaptcha" data-sitekey="{{ __SELF__.captchaSiteKey }}"></div>
        </div>
      {% endif %}
      {% if __SELF__.mailgunSubscribeExist %}
        <div class="form-group">
          <div class="checkbox">
            <label><input type="checkbox" name="maillist_subscribe" checked>{{label.maillist}}</label>
            <input type="hidden" name="confirm_subscribe" value="true">
          </div>
        </div>
      {% endif %}
      <button class="btn btn-primary btn-lg pull-right mt-4">
        {{label.button_text}}
      </button>
    </form>

The example uses standard partial `{{ __SELF__ }}::confirm` for displaying the contact confirmation message. Confirmation message will be displayed in
`.confirm-contact-container`. The default partial located in `plugins/grofgraf/contactme/components/contactform/confirm.htm`.

Email templates for contact and auto-reply can be customized under **Settings > Mail > Mail Templates**

## Authors

* [GrofGraf](https://github.com/GrofGraf)

## License

The MIT License (MIT)

Copyright (c) 2017 GrofGraf

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
