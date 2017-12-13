<?php namespace GrofGraf\ContactMe;

use Backend;
use System\Classes\PluginBase;

/**
 * ContactMe Plugin Information File
 */
class Plugin extends PluginBase
{
    /**
     * Returns information about this plugin.
     *
     * @return array
     */
    public function pluginDetails()
    {
        return [
            'name'        => 'ContactMe',
            'description' => 'Contact Form plugin for OctoberCMS',
            'author'      => 'GrofGraf',
            'icon'        => 'icon-envelope'
        ];
    }

    /**
     * Register method, called when the plugin is first registered.
     *
     * @return void
     */
    public function register()
    {

    }

    /**
     * Boot method, called right before the request route.
     *
     * @return array
     */
    public function boot()
    {

    }

    /**
     * Registers any front-end components implemented in this plugin.
     *
     * @return array
     */
    public function registerComponents()
    {
        return [
            'GrofGraf\ContactMe\Components\ContactForm' => 'contactForm',
        ];
    }

    /**
     * Registers any back-end permissions used by this plugin.
     *
     * @return array
     */
    public function registerPermissions()
    {
        return [
            'grofgraf.contactme.settings' => [
                'tab' => 'ContactMe',
                'label' => 'Contact Form Settings'
            ],
        ];
    }

    /**
     * Registers back-end navigation items for this plugin.
     *
     * @return array
     */
    public function registerNavigation()
    {
        return []; // Remove this line to activate

        return [
            'contactme' => [
                'label'       => 'ContactMe',
                'url'         => Backend::url('grofgraf/contactme/mycontroller'),
                'icon'        => 'icon-envelope',
                'permissions' => ['grofgraf.contactme.*'],
                'order'       => 500,
            ],
        ];
    }

    public function registerSettings(){
      return [
          'settings' => [
              'label'       => 'Contact Form',
              'description' => 'Settings for contact form',
              'category'    => 'Marketing',
              'icon'        => 'icon-envelope',
              'class'       => 'GrofGraf\ContactMe\Models\Settings',
              'order'       => 100,
              'permissions' => ['grofgraf.contactme.settings']
          ]
      ];
    }

    public function registerMailTemplates()
    {
        return [
            'grofgraf.contactme::emails.message' => 'Mail template for contact from website',
            'grofgraf.contactme::emails.auto-reply' => 'Mail template for auto reply',
        ];
    }
}
