<?php

 /**
 * KontentTweet - Tweets for Joomla
 * @version 	$Id: clearsessions.php
 * @package 	KontentTweet
 * @copyright 	(C) 2011 Kontent Design. All rights reserved.
 * @license 	http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link 		http://extensions.kontentdesign.com
 **/
/**
 * Clears PHP sessions and redirects to the connect page.
 */
 
/* Load and clear sessions */
session_start();
session_destroy();
 
/* Redirect to page with the connect to Twitter option. */
header('Location: ./connect.php');
