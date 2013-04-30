<?php 
/*
Plugin Name: Twitter Cache
Description: A Hard-coded solution to cache our twitter feed so we don't get rate-limited
Version: 20120830
Plugin URI: https://github.com/jackweinbender/tweet-cache-wordpress
Author: Jack Weinbender
Author URI: https://github.com/jackweinbender/
*/


include 'inc/TwitterCacheSettings.php';
include 'inc/TwitterCacheWidget.php';

$TwitterCacheSettings   = new TwitterCacheSettings();
$TwitterCacheWidget     = new TwitterCacheWidget($TwitterCacheSettings);

$TwitterCacheWidget->update_twtcache();

?>