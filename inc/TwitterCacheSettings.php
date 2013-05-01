<?php


class TwitterCacheSettings extends TwitterPlugin {

    /* Class variables to be used throughout */
    private $pluginOptionName = 'twitter_cache_plugin_settings';
    private $pluginSettingsPage = 'twitter_cache_settings_page';

    /* This is the main settings variable */
    private $settings;

	/* Constructor */
	public function __construct(){
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
                'timestamp'         => 0
            );

            /* Save defaults to DB */
            $this->saveSettings();

        }

        /* Add actions to admin area */
        if (is_admin()){
            add_action( 'admin_init', array($this, 'initialize_plugin_settings' )); 
            add_action( 'admin_menu', array($this, 'initialize_settings_page' )); 
        }
	}

    /* Registers all setting sections and setting fields */
    public function initialize_plugin_settings() { 
     
        // If the theme options don't exist, create them.  
        if( false == get_option( 'twitter_cache_plugin_settings' ) ) {    
            add_option( 'twitter_cache_plugin_settings' );  
        } // end if  
      
        /* Add a settings section */
        add_settings_section(  
            'twitter_cache_settings_section',
            'User Info',               
            array($this, 'settings_section_callback'),
            $this->pluginSettingsPage     
        );  
          
            /* Add all the settings fields */
            add_settings_field(
                'user_id',
                'User ID',
                array($this, 'setting_user_id_callback'),
                $this->pluginSettingsPage,
                'twitter_cache_settings_section',
                array(
                    'Your Twitter Username' 
                ) 
            ); 
             
            add_settings_field(  
                'tweets_to_cache',                      
                'Tweet Count',               
                array($this, 'setting_tweet_count_callback'),   
                $this->pluginSettingsPage,                    
                'twitter_cache_settings_section',          
                array(                               
                    'Number of tweets you wish to cache.' 
                ) 
            ); 
             
            add_settings_field(  
                'cache_length',                       
                'Cache Length',                
                array($this, 'setting_cache_length_callback'),    
                $this->pluginSettingsPage,         
                'twitter_cache_settings_section',          
                array(                               
                    'Cache length (in minutes).' 
                ) 
            );

        /* Add a settings section */
        add_settings_section(  
            'twitter_cache_cache_section',
            'Cache Info',               
            array($this, 'cache_section_callback'),
            $this->pluginSettingsPage  
        ); 

            add_settings_field(  
                'timestamp',                       
                'Last Cached',                
                array($this, 'setting_timestamp_callback'),    
                $this->pluginSettingsPage,        
                'twitter_cache_cache_section',          
                array(                               
                    'Delete this value to clear the cache.' 
                ) 
            ); 
            add_settings_field(  
                'json_object',                       
                'Tweets Cached',                
                array($this, 'setting_json_callback'),    
                $this->pluginSettingsPage,        
                'twitter_cache_cache_section'
            ); 
         
        /* Register Settings */
        register_setting( 
            $this->pluginSettingsPage,
            $this->pluginOptionName 
        ); 
    }

    /* Settings section callbacks (Empty) */
	public function settings_section_callback(){}
    public function cache_settings_callback(){}


    /* Setting field callbacks */
    public function setting_user_id_callback($args) { 
         
        $html = '<input type="text" id="user_id" name="twitter_cache_plugin_settings[user_id]" value="' . $this->settings['user_id'] . '" />';  
        $html .= '<label for="user_id"> '  . $args[0] . '</label>';   
         
        echo $html;
    }
 
    public function setting_tweet_count_callback($args) { 
         
        $html = '<input type="text" id="tweets_to_cache" name="twitter_cache_plugin_settings[tweets_to_cache]" value="' . $this->settings['tweets_to_cache'] . '" />';  
        $html .= '<label for="tweets_to_cache"> '  . $args[0] . '</label>';  
         
        echo $html; 
    }
 
    public function setting_cache_length_callback($args) { 
         
        $html = '<input type="text" id="cache_length" name="twitter_cache_plugin_settings[cache_length]" value="' . $this->settings['cache_length'] . '" />';  
        $html .= '<label for="cache_length"> '  . $args[0] . '</label>';   
          
        echo $html;  
    }  

    public function setting_timestamp_callback($args) { 
        if (!$this->settings['timestamp']){
            $html = '<code>NA</code>';
        } else {
            $html = '<code>' . date('j M Y g:i:s A', $this->settings['timestamp']) . '</code>';
        }
        echo $html;
    }

    public function setting_json_callback($args) {
        if (!$this->settings['json_object']){
            $html = '<code>No object cached. Make sure Username is correct.</code>';
        } else {
            $jsonObj = json_decode(urldecode($this->settings['json_object']));
            
            $html  = "<table class='widefat wp-list-table'>\n";
            $html .= "<thead>\n<tr>\n";
            $html .= "<th>Tweets</th>\n</tr>\n</thead>\n<tbody>\n";
            
            foreach ($jsonObj as $tweet) {
                $html .= "<tr>\n";
                $html .= '<td>' . $this->linkify($tweet->text) . '</td></tr>';
            }
            $html .= '</table>';
        }
        echo $html;
    }


    /* Saves current settings to DB */
    public function saveSettings(){

        update_option($this->pluginOptionName, $this->settings);
    }

    /* Updates settings from DB */
    public function fetchSettings(){
        $this->settings = get_option( $this->pluginOptionName );
    }

    /* Update and saves a setting */
    public function updateSetting($setting, $value){

        /* Reassign setting to new value*/
        $this->settings[$setting] = $value;

        /* Update database */
        update_option($this->pluginOptionName, $this->settings);
    }

    /* Returns all settings as array */
    public function getSettings(){
        return $this->settings;
    }

    /* Create Settings Page */
	public function initialize_settings_page() {  
        add_options_page(  
            'Tweet Cache',
            'Tweet Cache',
            'administrator',
            'twitter-cache-plugin-settings',
            array($this, 'display_settings_page_callback')
        );  
    }

    /* Callback function for displaying the settigs page */
    public function display_settings_page_callback() { 
        ?> 
            <!-- Create a header in the default WordPress 'wrap' container --> 
            <div class="wrap"> 
             
                <div id="icon-themes" class="icon32"></div> 
                <h2>Twitter Cache</h2> 
                <?php //settings_errors(); ?> 
                 
                <form method="post" action="options.php"> 
                    <?php settings_fields( $this->pluginSettingsPage ); ?> 
                    <?php do_settings_sections( $this->pluginSettingsPage ); ?>          
                    <?php submit_button(); ?> 
                </form> 
                 
            </div><!-- /.wrap --> 
        <?php 
    }
}

?>