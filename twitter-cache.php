<?php 
/*
Plugin Name: Twitter Cache
Description: A Hard-coded solution to cache our twitter feed so we don't get rate-limited
Version: 20120830
Plugin URI: http://library.milliagn.edu/
Author: Jack Weinbender
Author URI: http://library.milligan.edu/
*/


function twtchache_option_menu() {  
  
    add_options_page(  
        'Tweet Cache',            // The title to be displayed in the browser window for this page.  
        'Tweet Cache',            // The text to be displayed for this menu item  
        'administrator',            // Which type of users can see this menu item  
        'twtchache_plugin_options',    // The unique ID - that is, the slug - for this menu item  
        'twtchache_plugin_display_callback'     // The name of the function to call when rendering this menu's page  
    );  
  
} // end twtchache_option_menu  
add_action( 'admin_menu', 'twtchache_option_menu' ); 
 
/** 
 * Renders a simple page to display for the theme menu defined above. 
 */ 
function twtchache_plugin_display_callback() { 
?> 
    <!-- Create a header in the default WordPress 'wrap' container --> 
    <div class="wrap"> 
     
        <div id="icon-themes" class="icon32"></div> 
        <h2>Tweet-Cache</h2> 
        <?php settings_errors(); ?> 
         
        <form method="post" action="options.php"> 
            <?php settings_fields( 'twtchache_options_page' ); ?> 
            <?php do_settings_sections( 'twtchache_options_page' ); ?>          
            <?php submit_button(); ?> 
        </form> 
         
    </div><!-- /.wrap --> 
<?php 
} // end twtchache_plugin_display_callback 
 
function twtcache_initialize_plugin_options() { 
 
    // If the theme options don't exist, create them.  
    if( false == get_option( 'twtcache_options_section' ) ) {    
        add_option( 'twtcache_options_section' );  
    } // end if  
  
    // First, we register a section. This is necessary since all future options must belong to a   
    add_settings_section(  
        'twtchache_settings_section',         // ID used to identify this section and with which to register options  
        'Display Options',                  // Title to be displayed on the administration page  
        'twtchache_options_callback', // Callback used to render the description of the section  
        'twtchache_options_page'            // Page on which to add this section of options  
    );  
      
    // Next, we'll introduce the fields for toggling the visibility of content elements.  
    add_settings_field(   
        'user_id',                      // ID used to identify the field throughout the theme 
        'User ID',                           // The label to the left of the option interface element 
        'twtcache_user_id_callback',   // The name of the function responsible for rendering the option interface 
        'twtchache_options_page',           // The page on which this option will be displayed 
        'twtchache_settings_section',         // The name of the section to which this field belongs 
        array(                              // The array of arguments to pass to the callback. In this case, just a description. 
            'Your Twitter Username' 
        ) 
    ); 
     
    add_settings_field(  
        'tweets_to_cache',                      
        'Tweet Count',               
        'twtcache_tweet_count_callback',   
        'twtchache_options_page',                     
        'twtchache_settings_section',          
        array(                               
            'Number of tweets you wish to cache.' 
        ) 
    ); 
     
    add_settings_field(  
        'cache_length',                       
        'Cache Length',                
        'twtcache_cache_length_callback',    
        'twtchache_options_page',         
        'twtchache_settings_section',          
        array(                               
            'Cache length (in minutes).' 
        ) 
    ); 
    add_settings_field(  
        'timestamp',                       
        'Last Cached',                
        'twtcache_timestamp_callback',    
        'twtchache_options_page',         
        'twtchache_settings_section',          
        array(                               
            'Delete this value to clear the cache.' 
        ) 
    ); 
    add_settings_field(  
        'json-object',                       
        'Most Recent JSON Object',                
        'twtcache_json_callback',    
        'twtchache_options_page',         
        'twtchache_settings_section'
    ); 
     
    // Finally, we register the fields with WordPress 
    register_setting( 
        'twtchache_options_page', 
        'twtcache_options_section' 
    ); 


} // end twtcache_initialize_plugin_options 
add_action('admin_init', 'twtcache_initialize_plugin_options'); 
 
function twtchache_options_callback() { 
    echo '<p>Select which areas of content you wish to display.</p>'; 
} // end twtchache_options_callback 
 
function twtcache_user_id_callback($args) { 
   
    $options = get_option('twtcache_options_section', null); 
     
    $html = '<input type="text" id="user_id" name="twtcache_options_section[user_id]" value="' . $options['user_id'] . '" />';  
    $html .= '<label for="user_id"> '  . $args[0] . '</label>';   
     
    echo $html;
} // end twtcache_user_id_callback 
 
function twtcache_tweet_count_callback($args) { 
 
    $options = get_option('twtcache_options_section'); 
     
    $html = '<input type="text" id="tweets_to_cache" name="twtcache_options_section[tweets_to_cache]" value="' . $options['tweets_to_cache'] . '" />';  
    $html .= '<label for="tweets_to_cache"> '  . $args[0] . '</label>';  
     
    echo $html; 
     
} // end twtcache_tweet_count_callback 
 
function twtcache_cache_length_callback($args) { 
     
    $options = get_option('twtcache_options_section'); 
     
    $html = '<input type="text" id="cache_length" name="twtcache_options_section[cache_length]" value="' . $options['cache_length'] . '" />';  
    $html .= '<label for="cache_length"> '  . $args[0] . '</label>';   
      
    echo $html;  
      
} // end twtcache_cache_length_callback  

function twtcache_timestamp_callback($args) { 
     
    $options = get_option('twtcache_options_section');
    if (!$options['timestamp']){
        $html = '<code>Never</code>';   
    } else {
    $html = '<code>' . date('j M Y g:i:s A e', $options['timestamp']) . '</code>';
    $html .= '<input type="hidden" id="timestamp" name="twtcache_options_section[timestamp]" value="' . $options['timestamp'] . '" />';
    }
      
    echo $html;  
      
} // end twtcache_timestamp_callback

function twtcache_json_callback($args) { 
     
    $options = get_option('twtcache_options_section'); 
    if (!$options['json']){
        $html = '<code>No object cached.</code>';   
    } else {
    $html = '<code>' . urldecode($options['json']) . '</code>';
    $html .= '<input type="hidden" id="json" name="twtcache_options_section[json]" value="' . $options['json'] . '" />';
    }
      
    echo $html;  
    
} // end twtcache_json_callback


function update_twtcache(){
    $options = get_option('twtcache_options_section');
    
    if (($options['timestamp'] + 60 * $options['cache_length']) < time()){ 

        $jsonurl = "https://api.twitter.com/1/statuses/user_timeline.json?include_entities=true&include_rts=true&screen_name=" . $options['user_id'];
            if ($options['tweets_to_cache'] != null){
                $jsonurl .= "&count=" . $options['tweets_to_cache'];
            } else {
		$jsonurl .= "&count=3";
		}
        

        $json = file_get_contents($jsonurl,0,null,null);
        
        if ($json) {
            $options['timestamp'] = time();
            $options['json'] = urlencode($json);
            update_option('twtcache_options_section', $options );
        }
    }
}

add_action('wp_head', 'update_twtcache');
function twtcache() {
    do_action('twtcache');
}

function linkify($text){
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

function display_tweets(){
    $options = get_option('twtcache_options_section', null);
    $json = urldecode($options['json']);
    $json_obj = json_decode($json);
    
    foreach ($json_obj as $array) {
        $date_iso = date('c', strtotime($array->created_at));
        $date_str = date('j M Y', strtotime($array->created_at));
        echo '<div class="tweet"><div class="tweet-text">' . linkify($array->text) . '</div><div class="tweet-foot"><a href="https://twitter.com/MilliganLibrary/status/' . $array->id_str . '" class="timestamp" title="' . $date_iso . ' ">' . $date_str . '</a> | <a href="http://twitter.com/intent/tweet?in_reply_to=' . $array->id_str . '">reply</a> | <a href="http://twitter.com/intent/retweet?tweet_id=' . $array->id_str . '">retweet</a> | <a href="http://twitter.com/intent/favorite?tweet_id=' . $array->id_str . '">favorite</a></div></div>';

    }

}
add_action('twtcache', 'display_tweets');

?>