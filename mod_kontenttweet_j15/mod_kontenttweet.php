<?php 

 /**
 * KontentTweet - Tweets for Joomla
 * @version 	$Id: mod_kontenttweet.php
 * @package 	KontentTweet
 * @copyright 	(C) 2011 Kontent Design. All rights reserved.
 * @license 	http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link 		http://extensions.kontentdesign.com
 **/

defined( '_JEXEC' ) or die( 'Restricted access' );

require_once( dirname( __FILE__ ) . DS . 'helper.php' );

$twitter =& new modTweetsHelper( $params );
$username = $params->get('username');

if($params->get('timeline') == "friendsonly")
{
	$friends = array();
	if(is_array($twitter->tweets))
	{
	
		foreach ($twitter->tweets as $tweet)
		{
			if($tweet->user->screen_name != $username)
			{
				$friends[] = $tweet;
			}	
		}
	}
	
	$twitter->tweets = $friends;
}

require( JModuleHelper::getLayoutPath( 'mod_kontenttweet', $twitter->layout ) );
?>