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
			'Tweet-Cache', 				// Menu TItle
			'manage_options', 			// Permissions
			'twtcache', 				// Page name
			'twtcache_settings_page'	// Page callback (for content)
			);
	}

	/* Settings page Callback */
	function twtcache_settings_page(){ ?>
		<div id="wrap">
			<h1>Tweet Cache Settings</h1>
			<form>

			</form>
		</div>
		<?php
		}

/* On admin initialization of the plugin */
add_action(
	'admin_init', 
	'twtcache_admin_init' // Callback to register settings
	);

	/*Admin Init Callback */
	function twtcache_admin_init(){
		add_settings_section(
			'twtcache_settings', 				// Section ID
			'Tweet-Cache Settings', 			// Section 'Title' (For display)
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
	    	'twtcache_settings'//,         		// Section in which this field appears  
    		//array()                           // Array of arguments passed to the callback 
    	);
    	/* Cache Length */
    	add_settings_field(   
			'twt_cache_legth',                  // Field ID
			'Username',                         // Field label (for display)  
	    	'twt_cache_length_callback',   		// Callback to render the html form element 
			'twtcache',                         // Page on which this field appears
	    	'twtcache_settings'//,         		// Section in which this field appears  
    		//array()                           // Array of arguments passed to the callback 
    	);
	}

	/* Section Callback */
	function twtcache_settings_callback() { 
		echo '<p>Tweet-Cache settings</p>';
	}

	/* Field Callbacks */
		function twt_cache_user_callback($args) {
		    // Note the ID and the name attribute of the element match that of the ID in the call to add_settings_field  
		    $html = '<input type="text" id="twt_cache_tweet_count" name="twt_cache_tweet_count" value="' . get_option('twt_cache_tweet_count') . '" />';
		    $html .= '<label for="twt_cache_tweet_count"> '  . $args[0] . '</label>';   
		}
		function twt_cache_tweet_count_callback($args) {

		}
		function twt_cache_length_callback($args) {

		}

// $api_string = 'http://api.twitter.com/1/statuses/user_timeline/MilliganLibrary.json';
// $tweet_count = 2;
// $cache_expires = 60; // Minutes


// function renew_object() {
// 	$json = file_get_contents($api_string . "?count=" . $tweet_count);
// 	return $json;
// }

?>