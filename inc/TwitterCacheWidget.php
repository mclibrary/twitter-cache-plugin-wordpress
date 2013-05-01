<?php

class TwitterCacheWidget extends TwitterPlugin {

    private $sObj;
    private $settings;
    private $time;

    public function __construct(TwitterCacheSettings $settingsObject){

        $this->sObj = $settingsObject;
        $this->settings = $settingsObject->getSettings();

        add_action('twtcache', array($this, 'display_tweets'));
        add_action('wp_head', array($this, 'update_twtcache'));

        $this->update_twtcache();

    }

    /* Check if cache has expired */
    public function isCacheExpired(){

        $this->time = (int) current_time('timestamp', 0);

        if ( $this->settings['timestamp'] + $this->settings['cache_length'] * 60 < $this->time ){
            return true;
        } else {
            return false;
        }

    }

    public function twtcache() {
        do_action('twtcache');
    }

    public function update_twtcache(){

        if ($this->isCacheExpired()){ 

            $jsonurl  = "https://api.twitter.com/1/statuses/user_timeline.json?include_entities=true&include_rts=true&screen_name=";
            $jsonurl .= $this->settings['user_id'];
            $jsonurl .= "&count=" . $this->settings['tweets_to_cache'];
            
            $json = file_get_contents($jsonurl,0,null,null);

            if ($json) {
                $this->settings['timestamp'] = $this->time;
                $this->settings['json_object'] = urlencode($json);

                $this->sObj->updateSetting('timestamp', $this->settings['timestamp']);
                $this->sObj->updateSetting('json_object', $this->settings['json_object']);
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

?>