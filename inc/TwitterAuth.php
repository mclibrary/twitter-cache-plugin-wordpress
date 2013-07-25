<?php

class TwitterAuth extends TwitterPlugin {
	
	private $oauth;

	public function __construct(){
		parent::__construct();

		$this->oauth = array (
	        'access_token'          => 'Jack',
	        'access_token_secret'   => 'Weinbender',
	        'consumer_key'          => '',
	        'consumer_secret'       => '',
        );

		/* Get a new token */
		$newToken = $this->getToken();

		/* Update option */
		$this->updateSetting('bearer_token', $newToken);

	}

	public function hasCurl(){
	/* Checks to see if CURL extension is loaded */

		/* Is CURL installed? */
		if (in_array('curl', get_loaded_extensions()) ? true : false);

	}

	public function isTokenSet(){
	/* Checks to see if bearer token is already set in settings array */
		return array_key_exists('bearer_token', $this->settings);
	}

	public function getToken(){
	/* Fetches and retruns new bearer token */

		/* Encode Consumer Key and Secret */
		$twCreds = $this->encodeCredentials();
		
		/* Assign HTTP response to variable */
		$response = $this->postCypher($twCreds);

		$newToken = $this->parseResponse($response);

		/* Return bearer token */
		return $newToken;

	}

	public function encodeCredentials(){
	/* Encodes **Application Only** credentials into a hash for POST request
	   See: https://dev.twitter.com/docs/auth/application-only-auth */

	   	/* URL Encode Consumer Key and Secret */
		$consumerKey = 		urlencode($this->oauth['consumer_key']);
		$consumerSecret = 	urlencode($this->oauth['consumer_secret']);

		/* Concatenate URL encoded keys */
		$rawString =  $consumerKey . ":" . $consumerSecret;

		/* Return base64 encoded string */
		$encodedCred = base64_encode($rawString);

		return $encodedCred;

	}

	public function postCypher($cypherText){
	/* POSTs encoded user credentials to Twitter OAuth
	   Returns HTTP response with bearer token */

		$url = "https://api.twitter.com/oauth2/token/"; // url to send data to for authentication
		$headers = array( 
			"POST /oauth2/token HTTP/1.1", 
			"Host: api.twitter.com", 
			"User-Agent: Twitter Application-only OAuth App v.1",
			"Authorization: Basic ".$cypherText."",
			"Content-Type: application/x-www-form-urlencoded;charset=UTF-8", 
			"Content-Length: 29"
		); 


		$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL,$url);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
        	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        	curl_setopt($ch, CURLOPT_CAINFO, __DIR__ . '/cacert.pem');
			curl_setopt($ch, CURLOPT_POSTFIELDS, "grant_type=client_credentials");
		$header = curl_setopt($ch, CURLOPT_HEADER, 1);
		$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		$retrievedhtml = curl_exec($ch);
		
		/*Uncomment for Debugging */
		// echo curl_error($ch);
		
		curl_close($ch);

		return $retrievedhtml;
	   
	}

	public function parseResponse($html){

		$lines = explode("\n", $html);

		foreach ($lines as $line) {
			$json = json_decode($line);

			if ($json && $json->token_type == 'bearer') {
				return $json->access_token;
			}
		}

	}

}

