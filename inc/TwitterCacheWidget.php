<?php

class TwitterCacheWidget{

    private $sObj;
    private $settings;

    public function __construct(TwitterCacheSettings $settingsObject){

        $this->sObj = $settingsObject;
        $this->settings = $settingsObject->getSettings();

        add_action('twtcache', array($this, 'display_tweets'));
        add_action('wp_head', array($this, 'update_twtcache'));

    }

    public function twtcache() {
        do_action(array($this,'twtcache'));
    }

    public function update_twtcache(){

        if (true){ 

            $jsonurl  = "https://api.twitter.com/1/statuses/user_timeline.json?include_entities=true&include_rts=true&screen_name=";
            $jsonurl .= $this->settings['user_id'];
            $jsonurl .= "&count=" . $this->settings['tweets_to_cache'];
            
            $json = file_get_contents($jsonurl,0,null,null);

            if ($json) {
                $this->settings['timestamp'] = time();
                $this->settings['json_object'] = urlencode($json);

                $this->sObj->updateSetting('timestamp', $this->settings['timestamp']);
                $this->sObj->updateSetting('json_object', $this->settings['json_object']);
            }
        }
    }

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

    public function display_tweets(){
        $json = urldecode($this->settings['json_object']);
        $json_obj = json_decode($json);
        
        foreach ($json_obj as $array) {
            $date_iso = date('c', strtotime($array->created_at));
            $date_str = date('j M Y', strtotime($array->created_at));
            echo '<div class="tweet"><div class="tweet-text">' . linkify($array->text) . '</div><div class="tweet-foot"><a href="https://twitter.com/MilliganLibrary/status/' . $array->id_str . '" class="timestamp" title="' . $date_iso . ' ">' . $date_str . '</a> | <a href="http://twitter.com/intent/tweet?in_reply_to=' . $array->id_str . '">reply</a> | <a href="http://twitter.com/intent/retweet?tweet_id=' . $array->id_str . '">retweet</a> | <a href="http://twitter.com/intent/favorite?tweet_id=' . $array->id_str . '">favorite</a></div></div>';
        }
    }
}

?>