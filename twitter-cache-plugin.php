<?php 
/*
Plugin Name: Twitter Cache
Description: A simple plugin for caching, updating, and displaying Tweets
Version: 20120830
Plugin URI: https://github.com/jackweinbender/tweet-cache-wordpress
Author: Jack Weinbender
Author URI: https://github.com/jackweinbender/
*/


include 'inc/Twitter.php';
include 'inc/TwitterAuth.php';
include 'inc/TwitterCache.php';
include 'inc/TwitterSettingsPage.php';
include 'inc/TwitterWidget.php';


$twitterWidget = new TwitterWidget();

/* Settings Page */
$twitterSettingsPage = new TwitterSettingsPage();

	if (!$twitterSettingsPage->isBearerTokenSet()){
		$twitterAuth = new TwitterAuth();
		$twitterSettingsPage->refreshSettings();
	}

$twitterCache = new TwitterCache();
$twitterSettingsPage->refreshSettings();
