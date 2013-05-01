<?php

abstract class TwitterPlugin {
	
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
}

?>