<?php

 /**
 * KontentTweet - Tweets for Joomla
 * @version 	$Id: helper.php
 * @package 	KontentTweet
 * @copyright 	(C) 2011 Kontent Design. All rights reserved.
 * @license 	http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link 		http://extensions.kontentdesign.com
 **/

defined( '_JEXEC' ) or die( 'Restricted access' );

class modTweetsHelper {
	
	var $tweets = '';
	
	var $format = false;
	
	var $layout = 'default';
	
	var $errormessage = '';
	
	/**
	* The class constructor
	* 
	* The Tweets module Helper constructor
	* 
	* @access public
	* @param object $params The Joomla! parameter object
	* @return object The ModTweetsHelper object
	*/
	function modTweetsHelper( $params ) {
		
		if( $this->_checkCompatibility() ) {
			
			$this->format = $this->_checkFormat();
			
			if( $this->format ) {
			
				if( $params->get( 'enable-cache' ) === '1' ) {
				
					$cache =& JFactory::getCache();
				
					$cache->setCaching( true );
					$cache->setLifeTime( $params->get( 'cache-time', 30 ) * 60 );
				
					$this->tweets = $cache->get( array( $this, 'getTweets' ), array( $params ) );
				
				} else {
				
					$this->tweets = $this->getTweets( $params );
				
				}
				
				if( !$this->tweets ) {
					
					$this->layout = 'error';
					
				}
				
			} else {
				
				$this->layout = 'error';
				
				$this->errormessage = JText::_( 'MOD_KONTENTTWEET_ERROR_XML_JSON' );
				
			}
			
		} else {
			
			$this->layout = 'error';
			
			$this->errormessage = JText::_( 'MOD_KONTENTTWEET_ERROR_PHP_VERSION' );
			
		}
		
		return $this;
		
	}
	
	/**
	* Get the latest statuses from Twitter
	* 
	* Get the latest statuses from Twitter
	* 
	* @access public
	* @param object $params The Joomla! parameter object
	* @return mixed Returns false on failure, object on success
	*/
	function getTweets( $params ) {
		
		require_once('includes/twitteroauth/twitteroauth.php');
		require_once('class.twitter.php');
		$consumer_key = $params->get('consumer_key');
		$consumer_secret = $params->get('consumer_secret');
		$oauth_token = $params->get('oauth_token');
		$oauth_token_secret = $params->get('oauth_token_secret');
		$callback_url = $params->get('callback_url');
		
		/* Build TwitterOAuth object with client credentials. */
		//$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET);
		 
		/* Get temporary credentials. */
		
		$connection = new TwitterOAuth($consumer_key, $consumer_secret, $oauth_token, $oauth_token_secret, $callback_url);
		
		//$request_token = $connection->getRequestToken(CONSUMER_KEY, CONSUMER_SECRET, OAUTH_CALLBACK);
		//user_timeline
		//friends_timeline
		//home_timeline
		
		//$this->tweets = $connection->get("statuses/friends_timeline");
		
		$twitter = new Twitter( $params->get( 'username' ), $params->get( 'password' ), $this->format );
		
		$count = intval( $params->get( 'count' ) );
		
		if( $count === 0 ) {
			
			$count = 5;
			
		} elseif( $count > 200 ) {
			
			$count = 200;
			
		}
		
		$count = $params->get('count');
		$parameters = array();
		$parameters['count'] = $count;
		
		switch( $params->get( 'timeline' ) ) {
			case 'user':
				$twitter->output = $connection->get("statuses/user_timeline", $parameters);
				break;
			
			case 'friends':
				$twitter->output = $connection->get("statuses/friends_timeline", $parameters);
				break;
				break;
			case 'friendsonly':
				$twitter->output = $connection->get("statuses/friends_timeline");
				break;
			default:
				$twitter->output = $connection->get("statuses/user_timeline", $parameters);
				break;
		}
	
		
		if( $twitter->output ) {
			
			$twitter->status = "1";
		} else {
			
			$this->errormessage = JText::_( 'MOD_KONTENTTWEET_ERROR_SERVER_RESPONSE' ) .' '. $twitter->status;
			
			return false;
			
		}
		
		return $twitter->output;
		
	}
	
	/**
	* Decode the returned XML
	* 
	* Decodes the XML string returned from the Twitter class
	* 
	* @access private
	* @param mixed $output The output string returned by the Twitter class
	* @return array Returns an array of Twitter statuses
	*/
	function _decodeXML( $output ) {
		
		$statuses = array();
		
		$xml = simplexml_load_string( $output );
		
		foreach( $xml as $status ) {
			
			$statuses[] = $status;
			
		}
		
		return $statuses;
		
	}
	
	/**
	* Decode the returned JSON
	* 
	* Decodes the JSON string returned from the Twitter class
	* 
	* @access private
	* @param mixed $output The output string returned by the Twitter class
	* @return array Returns an array of Twitter statuses
	*/
	function _decodeJSON( $output ) {
		return json_decode( $output );
	}
	
	/**
	* Select either JSON or XML format
	* 
	* Check whether there is JSON support or XML support and return the format
	* 
	* @access private
	* @return string Return lowercase name of format
	*/
	function _checkFormat() {
		
		if( function_exists( 'json_decode' ) ) {
			
			return 'json';
			
		} elseif( function_exists( 'simplexml_load_string' ) ) {
			
			return 'xml';
			
		}
		
		return false;
		
	}
	
	/**
	* Check for PHP Compatibility
	* 
	* Checks if the PHP version on the server is higher than 5
	* 
	* @access private
	* @return boolean Returns false on failure, true on succes
	*/
	function _checkCompatibility() {
		
		return version_compare( PHP_VERSION, '5.0.0', '>' );
		
	}
	
}
?>