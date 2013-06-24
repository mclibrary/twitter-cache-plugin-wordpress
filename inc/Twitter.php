<?php

abstract class TwitterPlugin {

    /* Class variables to be used throughout */
    protected $pluginOptionName = 'twitter_cache_plugin_settings';
    protected $pluginSettingsPage = 'twitter_cache_settings_page';

    protected $settings;

    public function __construct(){

        $this->initialize_settings();

    }

    /* Get settings or set defaults */
    public function initialize_settings(){

        /* Retrieve plugin option, returns false if not set */
        $this->settings     = get_option( $this->pluginOptionName );
        
        /* If the option hasn't been set */
        if( $this->settings == false ) {
            
            /* Add the option to DB */
            add_option( $this->pluginOptionName );
            
            /* Set defaults */
            $this->settings = array(
                'user_id'           => 'twitter',
                'tweets_to_cache'   => '3',
                'cache_length'      => '5',
                'timestamp'         => NULL,
                'bearer_token'      => NULL,
            );

            /* Save defaults to DB */
            update_option($this->pluginOptionName, $this->settings);
        }
    }

    public function updateSetting($key, $value){

        $this->settings[$key] = $value;

        update_option($this->pluginOptionName, $this->settings);

        $this->settings = get_option( $this->pluginOptionName );

    }

    public function refreshSettings(){

        $this->settings = get_option( $this->pluginOptionName );

    }

    public function linkify($text){
        //http and https links
        $text = preg_replace(
        '@(https?://([-\w\.]+)+(/([\w/_\.]*(\?\S+)?(#\S+)?)?)?)@',
         '<a href="$1">$1</a>',
        $text);

        //@ mentions
        $text = preg_replace(
            '/@(\w+)/',
            '<a href="http://twitter.com/$1">@$1</a>',
            $text);

        // Hashtags
        $text = preg_replace(
        '/\s+#(\w+)/',
        ' <a href="http://search.twitter.com/search?q=%23$1">#$1</a>',
        $text);

        return $text;
    }

    public function isBearerTokenSet(){

        if ($this->settings['bearer_token'] == NULL){
            return false;
        } else {
            return true;
        }

    }
}