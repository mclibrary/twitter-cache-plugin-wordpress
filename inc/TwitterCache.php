<?php

class TwitterCache extends TwitterPlugin {

    private $time;

    public function __construct(){

        parent::__construct();

        add_action('twtcache', array($this, 'display_tweets'));
        
        $this->update_cache();

    }

    /* Check if cache has expired */
    public function isCacheExpired(){
        $this->time = (int) current_time('timestamp', 0);
        if ( $this->settings['timestamp'] == NULL ) {
            //echo "NULL\n";
            return true;
        } else {
            if ( $this->settings['timestamp'] + $this->settings['cache_length'] * 60 < $this->time ){
                //echo "Expired\n";
                return true;
            } else {
                //echo "Fresh\n";
                return false;
            }
        }
    }

    public function twtcache() {
        do_action('twtcache');
    }

    public function update_cache(){
        if ($this->isCacheExpired()){

            $jsonurl  = "https://api.twitter.com/1.1/statuses/user_timeline.json?include_entities=true&include_rts=true&screen_name=";
                $jsonurl .= $this->settings['user_id'];
                $jsonurl .= "&count=" . $this->settings['tweets_to_cache'];

            $opts = array (
                'http'=>array (
                    'method'=>"GET",
                    'header'=>"Authorization: Bearer " . $this->settings['bearer_token'] . "\r\n"
                    )
                );
            $context = stream_context_create($opts);
            
            $json = file_get_contents($jsonurl,false,$context,null);

            if ($json) {
                $this->updateSetting('timestamp', $this->time);
                $this->updateSetting('json_object', urlencode($json));
            }
        }
    }

    public function display_tweets(){

        $json = urldecode($this->settings['json_object']);
        $json_obj = json_decode($json);
        
        foreach ($json_obj as $tweet) {
            $date_iso = date('c', strtotime($tweet->created_at));
            $date_str = date('j M Y', strtotime($tweet->created_at));
            
            $html = '<div class="tweet">';
                $html .= '<div class="tweet-text">' . $this->linkify($tweet->text) . '</div>';
                $html .= '<div class="tweet-foot">';
                    $html .= '<a href="https://twitter.com/MilliganLibrary/status/';
                        $html .= $tweet->id_str . '"';
                        $html .= 'class="timestamp" title="' . $date_iso . ' ">' . $date_str . '</a>';
                    $html .= ' | ';
                    $html .= '<a href="http://twitter.com/intent/tweet?in_reply_to=' . $tweet->id_str . '">reply</a>';
                    $html .= ' | ';
                    $html .= '<a href="http://twitter.com/intent/retweet?tweet_id=' . $tweet->id_str . '">retweet</a>';
                    $html .= ' | ';
                    $html .= '<a href="http://twitter.com/intent/favorite?tweet_id=' . $tweet->id_str . '">favorite</a>';
                $html .= '</div>';
            $html .= '</div>';
            
            echo $html;
        }
    }

}