tweet-cache-wordpress
=====================

Wordpress plugin to cache your twitter timline into an option field and serve it locally in JSON format via AJAX.

Currently, the plugin caches a single JSON object of a user's timeline as an option field. It's attached to the wp_head() action hook and retrieves the specfied number of tweets as new JSON object.