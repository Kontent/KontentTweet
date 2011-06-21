<?php 

 /**
 * KontentTweet - Tweets for Joomla
 * @version 	$Id: error.php
 * @package 	KontentTweet
 * @copyright 	(C) 2011 Kontent Design. All rights reserved.
 * @license 	http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link 		http://extensions.kontentdesign.com
 **/

defined( '_JEXEC' ) or die( 'Restricted access' ); ?>

<?php if( $params->get( 'enable-css' ) == 1 ) : 
	
	$document = &JFactory::getDocument();
	$document->addStyleSheet( 'modules/mod_kontenttweet/tmpl/css/tweets.css' );
	
endif; ?>

<div class="tweet-error">
	
	<strong><?php echo JText::_( 'MOD_KONTENTTWEET_ERROR_TITLE' ); ?></strong>
	
	<p>
		<?php echo JText::_( 'MOD_KONTENTTWEET_ERROR_DESCRIPTION' ); ?>
	</p>
	
	<p><?php echo $twitter->errormessage; ?></p>
	
</div>