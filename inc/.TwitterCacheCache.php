<?php

class TwitterCacheCache extends TwitterPlugin {

	public function __construct() {

		/* Ensure that settings are initialized */
		parent::__construct();

	}


	
    public function saveSettings(){
	/* Saves current settings to DB */

		/* Update database to current variable values */
        update_option($this->pluginOptionName, $this->settings);

    }

    
    public function fetchSettings(){
    /* Updates settings from DB */

    	/* Reassigns settings variable to database values */
        $this->settings = get_option( $this->pluginOptionName );
    }

    
    public function updateSetting($setting, $value){
    /* Updates and saves a setting */

        /* Reassign setting to new value*/
        $this->settings[$setting] = $value;

        /* Update database */
        update_option($this->pluginOptionName, $this->settings);
    }

    
    public function getSettings(){
	/* Returns all settings as array */

    	/* Return current settings variable */
        return $this->settings;
    }

}