<?php 

 /**
 * KontentTweet - Tweets for Joomla
 * @version 	$Id: class.twitter.php
 * @package 	KontentTweet
 * @copyright 	(C) 2011 Kontent Design. All rights reserved.
 * @license 	http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link 		http://extensions.kontentdesign.com
 **/

defined( '_JEXEC' ) or die( 'Restricted Access' );

class Twitter {
	
	var $credentials = '';
	
	var $url = '';
	
	var $output = false;
	
	var $status = '';
	
	var $format = '';
	
	/**
	* Twitter class constructor
	*
	* The constructor for the Twitter class. Sets the username and password in a cURL readable format.
	*
	* @access public 
	* @param string $username Twitter username (e.g. jane.doe@mail.com)
	* @param string $password Twitter password
	*/
	function Twitter( $username, $password, $format ) {
		
		$this->credentials = sprintf( "%s:%s", $username, $password );
		
		$this->format = $format;
		
	}
	
	/**
	* Access Twitter class functions through a callback
	*
	* Places a callback to the Twitter class functions. Checks wether the given method exists and is callable.
	*
	* @access public
	* @param string $function The function to call
	* @param array $options Options to pass to the function
	*/
	function get( $function, $count = 0 ) {
		
		if( method_exists( $this, $function ) ) {
			
			if( is_callable( array( $this, $function ) ) ) {
				
				call_user_func( array( $this, $function ), $count );
				
			}
			
		}
		
	}
	
	
	/**
	* Get a Twitter timeline for a user
	* 
	* Get the Twitter timeline for the authenticated user. Retrieves the last 20 statuses for this user.
	* 
	* @access private
	*/
	function userTimeLine( $count ) {
		
		$this->url = 'http://twitter.com/statuses/user_timeline.'. $this->format .'?count='. $count;
		
		$this->callAPI();
		
	}
	
	/**
	* Get a Twitter timeline for a user and friends
	* 
	* Get the Twitter timeline for the authenticated user. Retrieves the last 20 statuses for this user and his friends.
	* 
	* @access private
	*/
	function friendsTimeLine( $count ) {
		
		$this->url = 'http://twitter.com/statuses/friends_timeline.'. $this->format .'?count='. $count;
		
		$this->callAPI();
		
	}
	
	/**
	* Place a call to the Twitter API
	* 
	* Places a call to the Twitter API using cURL.
	* 
	* @access private
	*/
	function callAPI() {
		
		$handle = curl_init();
		
		curl_setopt( $handle, CURLOPT_URL, $this->url );
		curl_setopt( $handle, CURLOPT_USERPWD, $this->credentials );
		curl_setopt( $handle, CURLOPT_RETURNTRANSFER, true );				
		
		$this->output = curl_exec( $handle );
				
		$this->status = curl_getinfo( $handle, CURLINFO_HTTP_CODE );
		
		curl_close( $handle );
		
	}
	
}
?>