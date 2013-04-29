<?php

class TwitterCacheSettings {

	public function __construct(){
		

		add_action('admin_init', 'initialize_plugin_settings'); 
		add_action( 'admin_menu', 'initialize_settings_page' ); 
	}

	function initialize_settings_page() {  
	  
	    add_options_page(  
	        'Tweet Cache',            // The title to be displayed in the browser window for this page.  
	        'Tweet Cache',            // The text to be displayed for this menu item  
	        'administrator',            // Which type of users can see this menu item  
	        'twitter-cache-plugin-settings',    // The unique ID - that is, the slug - for this menu item  
	        array($this, 'display_settings_page_callback'),
	    );  
	}

 
	/* Callback function for displaying the settigs page */
	function display_settings_page_callback() { 
		?> 
		    <!-- Create a header in the default WordPress 'wrap' container --> 
		    <div class="wrap"> 
		     
		        <div id="icon-themes" class="icon32"></div> 
		        <h2>Tweet-Cache</h2> 
		        <?php settings_errors(); ?> 
		         
		        <form method="post" action="options.php"> 
		            <?php settings_fields( 'twitter_cache_options_page' ); ?> 
		            <?php do_settings_sections( 'twitter_cache_options_page' ); ?>          
		            <?php submit_button(); ?> 
		        </form> 
		         
		    </div><!-- /.wrap --> 
		<?php 
	}
 
	function initialize_plugin_settings() { 
	 
	    // If the theme options don't exist, create them.  
	    if( false == get_option( 'twitter_cache_plugin_settings' ) ) {    
	        add_option( 'twitter_cache_plugin_settings' );  
	    } // end if  
	  
	    /* Add a settings section */
	    add_settings_section(  
	        'twitter_cache_settings_section',
	        'Display Options',               
	        array($this, 'settings_section_callback'),
	        'twitter_cache_options_page'     
	    );  
	      
	    /* Add all the settings fields */
	    add_settings_field(
	        'user_id',
	        'User ID',
	        array($this, 'setting_user_id_callback'),
	        'twitter_cache_options_page',
	        'twitter_cache_settings_section',
	        array(
	            'Your Twitter Username' 
	        ) 
	    ); 
	     
	    add_settings_field(  
	        'tweets_to_cache',                      
	        'Tweet Count',               
	        array($this, 'setting_tweet_count_callback'),   
	        'twitter_cache_options_page',                     
	        'twitter_cache_settings_section',          
	        array(                               
	            'Number of tweets you wish to cache.' 
	        ) 
	    ); 
	     
	    add_settings_field(  
	        'cache_length',                       
	        'Cache Length',                
	        array($this, 'setting_cache_length_callback'),    
	        'twitter_cache_options_page',         
	        'twitter_cache_settings_section',          
	        array(                               
	            'Cache length (in minutes).' 
	        ) 
	    ); 
	    add_settings_field(  
	        'timestamp',                       
	        'Last Cached',                
	        array($this, 'setting_timestamp_callback'),    
	        'twitter_cache_options_page',         
	        'twitter_cache_settings_section',          
	        array(                               
	            'Delete this value to clear the cache.' 
	        ) 
	    ); 
	    add_settings_field(  
	        'json-object',                       
	        'Most Recent JSON Object',                
	        array($this, 'setting_json_callback'),    
	        'twitter_cache_options_page',         
	        'twitter_cache_settings_section'
	    ); 
	     
	    /* Register Settings */
	    register_setting( 
	        'twitter_cache_options_page', 
	        'twitter_cache_plugin_settings' 
	    ); 
	}

 

	/* Settings callback functions */

	function settings_section_callback() { 
	    echo '<p>Settings for Twitter Cache Plugin</p>'; 
	}
 
	function setting_user_id_callback($args) { 
	   
	    $options = get_option('twitter_cache_plugin_settings', null); 
	     
	    $html = '<input type="text" id="user_id" name="twitter_cache_plugin_settings[user_id]" value="' . $options['user_id'] . '" />';  
	    $html .= '<label for="user_id"> '  . $args[0] . '</label>';   
	     
	    echo $html;
	}
 
	function setting_tweet_count_callback($args) { 
	 
	    $options = get_option('twitter_cache_plugin_settings'); 
	     
	    $html = '<input type="text" id="tweets_to_cache" name="twitter_cache_plugin_settings[tweets_to_cache]" value="' . $options['tweets_to_cache'] . '" />';  
	    $html .= '<label for="tweets_to_cache"> '  . $args[0] . '</label>';  
	     
	    echo $html; 
	     
	}
 
	function setting_cache_length_callback($args) { 
	     
	    $options = get_option('twitter_cache_plugin_settings'); 
	     
	    $html = '<input type="text" id="cache_length" name="twitter_cache_plugin_settings[cache_length]" value="' . $options['cache_length'] . '" />';  
	    $html .= '<label for="cache_length"> '  . $args[0] . '</label>';   
	      
	    echo $html;  
	      
	}  

	function setting_timestamp_callback($args) { 
	     
	    $options = get_option('twitter_cache_plugin_settings');
	    if (!$options['timestamp']){
	        $html = '<code>Never</code>';   
	    } else {
	    $html = '<code>' . date('j M Y g:i:s A e', $options['timestamp']) . '</code>';
	    $html .= '<input type="hidden" id="timestamp" name="twitter_cache_plugin_settings[timestamp]" value="' . $options['timestamp'] . '" />';
	    }
	      
	    echo $html;  
	      
	}

	function setting_json_callback($args) { 
	     
	    $options = get_option('twitter_cache_plugin_settings'); 
	    if (!$options['json']){
	        $html = '<code>No object cached.</code>';   
	    } else {
	    $html = '<code>' . urldecode($options['json']) . '</code>';
	    $html .= '<input type="hidden" id="json" name="twitter_cache_plugin_settings[json]" value="' . $options['json'] . '" />';
	    }
	      
	    echo $html;  
	    
	}

}