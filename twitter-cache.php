<?php 
/*
Plugin Name: Twitter Cache
Description: A Hard-coded solution to cache our twitter feed so we don't get rate-limited
Version: 20120830
Plugin URI: http://library.milliagn.edu/
Author: Jack Weinbender
Author URI: http://library.milligan.edu/
*/


/* Adds the plugin Settings page */
add_action(
	'admin_menu', 
	'plugin_admin_add_page' // Callback
	);

	/* Admin menu Callback */
	function plugin_admin_add_page() {
		add_options_page(
			'Tweet Cache Settings', 	// Page Title (for display)
			'Tweet-Cache', 				// Menu Title
			'manage_options', 			// Permissions
			'twtcache', 				// Page name
			'twtcache_settings_page'	// Page callback (for content)
			);
	}

	/* Settings page Callback */
	function twtcache_settings_page(){ ?>
	    <div class="wrap">  
	  
	        <!-- Add the icon to the page -->  
	        <div id="icon-themes" class="icon32"></div>  
	        <h2>Tweet-Cache Settings</h2>  
	  
	        <!-- Make a call to the WordPress function for rendering errors when settings are saved. -->  
	        <?php settings_errors(); ?>  
	  
	        <!-- Create the form that will be used to render our options -->  
	        <form method="post" action="options.php">  
	            <?php settings_fields( 'twtcache_settings' ); ?>  
	            <?php do_settings_sections( 'twtcache_settings' ); ?>             
	            <?php submit_button(); ?>  
	        </form>  
	    </div><!-- /.wrap -->  
	<?php  
		}

/* On admin initialization of the plugin */
add_action(
	'admin_init', 
	'twtcache_admin_init' // Callback to register settings
	);

	/*Admin Init Callback */
	function twtcache_admin_init(){
		if( false == get_option( 'twtcache_settings' ) ) {    
    		add_option( 'twtcache_settings' );  
		}
		add_settings_section(
			'twtcache_settings', 				// Section ID
			'Settings', 						// Section 'Title' (For display)
			'twtcache_settings_callback', 		// Callback function to render the description of the section
			'twtcache'							// Page on which this section appears
		);
		/* Twitter Username */
		add_settings_field(   
			'twt_cache_user',                   // Field ID
			'Username',                         // Field label (for display)  
	    	'twt_cache_user_callback',   		// Callback to render the html form element 
			'twtcache',                         // Page on which this field appears
	    	'twtcache_settings',         		// Section in which this field appears  
    		array(
    			'The Twitter user whose fed you wish to display.'
    		)                          			 // Array of arguments passed to the callback 
    	);
		/* Tweet Count */
    	add_settings_field(   
			'twt_cache_tweet_count',            // Field ID
			'Username',                         // Field label (for display)  
	    	'twt_cache_tweet_count_callback',	// Callback to render the html form element 
			'twtcache',                         // Page on which this field appears
	    	'twtcache_settings',         		// Section in which this field appears  
    		array(
    			'The Twitter user whose fed you wish to display.'
    		)
    	);
    	/* Cache Length */
    	add_settings_field(   
			'twt_cache_legth',                  // Field ID
			'Username',                         // Field label (for display)  
	    	'twt_cache_length_callback',   		// Callback to render the html form element 
			'twtcache',                         // Page on which this field appears
	    	'twtcache_settings',         		// Section in which this field appears  
    		array(
    			'The Twitter user whose fed you wish to display.'
    		)
    	);
    	register_setting(  
    		'twtcache_settings',  
    		'twtcache_settings'  
		); 
	}

	/* Section Callback */
	function twtcache_settings_callback() { 
		echo '<p>Tweet-Cache settings</p>';
	}

	/* Field Callbacks */
		function twt_cache_user_callback($args) {
		    // Note the ID and the name attribute of the element match that of the ID in the call to add_settings_field  
		   $options = get_option('twtcache_settings');
		   echo $options['twt_cache_user'];
		}

		function twt_cache_tweet_count_callback($args) {
			$options = get_option('twtcache_settings');
			echo $options['twt_cache_tweet_count'];

		}
		function twt_cache_length_callback($args) {
			$options = get_option('twtcache_settings');
			echo $options['twt_cache_legth'];

		}

// $api_string = 'http://api.twitter.com/1/statuses/user_timeline/MilliganLibrary.json';
// $tweet_count = 2;
// $cache_expires = 60; // Minutes


// function renew_object() {
// 	$json = file_get_contents($api_string . "?count=" . $tweet_count);
// 	return $json;
// }

?>