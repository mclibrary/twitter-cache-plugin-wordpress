<?php 
/*
Plugin Name: Twitter Cache
Description: A simple plugin for caching, pdating, and displaying Tweets
Version: 20120830
Plugin URI: https://github.com/jackweinbender/tweet-cache-wordpress
Author: Jack Weinbender
Author URI: https://github.com/jackweinbender/
*/

include 'inc/TwitterPlugin.php';
include 'inc/TwitterCacheSettings.php';
include 'inc/TwitterCacheWidget.php';

$TwitterCacheSettings   = new TwitterCacheSettings();
$TwitterCacheWidget     = new TwitterCacheWidget($TwitterCacheSettings);

?>