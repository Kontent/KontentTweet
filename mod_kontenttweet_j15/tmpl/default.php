<?php 

 /**
 * KontentTweet - Tweets for Joomla
 * @version 	$Id: default.php
 * @package 	KontentTweet
 * @copyright 	(C) 2011 Kontent Design. All rights reserved.
 * @license 	http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link 		http://extensions.kontentdesign.com
 **/

defined( '_JEXEC' ) or die( 'Restricted access' ); ?>

<?php if( $params->get( 'enable-css' ) === '1' ) : 
	$document = &JFactory::getDocument();
	$document->addStyleSheet( 'modules/mod_kontenttweet/tmpl/css/tweets.css' );
endif; 

$twitname = $params->get('username');
$numoftweets =  $params->get('count');

if($numoftweets > count($twitter->tweets )) $numoftweets = count($twitter->tweets );
?>
<!--[if IE]><style type="text/css">li.tweet-username a,li.tweet-message a,li.tweet-message{word-wrap: break-word;}</style><![endif]-->

<?php if( $params->get( 'show-link' ) === '1' ) : ?>
		<p class="tweet-follow"><a href="http://twitter.com/<?php echo $twitname; ?>" rel="external"><?php echo JText::_( 'MOD_KONTENTTWEET_FOLLOW' ); ?> <?php echo $twitname; ?></a></p>
<?php endif; ?>

<ul class="tweets-module">
<?php for( $i = 0, $c = $numoftweets ; $i < $c; $i++ ) : ?>
	
	<?php $tweet = $twitter->tweets[$i];?>
	
	<li class="tweet">
		<ul class="tweet-info">
		<?php if( $params->get( 'show-image' ) === '1' ) : ?>
			<li class="tweet-image">
				<img src="<?php echo $tweet->user->profile_image_url; ?>" alt="<?php echo $tweet->user->name; ?>" />
			</li>
		<?php endif; ?>

			<?php if( $params->get( 'show-username' ) === '1' ) : ?>
				<li class="tweet-username">
					<a href="http://twitter.com/<?php echo $tweet->user->screen_name; ?>" rel="external"><?php echo $tweet->user->name; ?></a>
				</li>
			<?php endif; ?>
		
			<?php if( $params->get( 'show-date' ) === '1' ) : ?>
				<?php
				$now =& JFactory::getDate();
				$date =& JFactory::getDate( strtotime( $tweet->created_at ) + ( $now->toUnix() - time() ) );
				$diff = $now->toUnix() - $date->toUnix();
							
				if( $diff < 60 ) {
					$created_date = JText::_( 'MOD_KONTENTTWEET_LESS_THAN_A_MINUTE_AGO' );
				} elseif( $diff < 120 ) {
					$created_date = JText::_( 'MOD_KONTENTTWEET_ABOUT_A_MINUTE_AGO' );
				} elseif( $diff < ( 45 * 60 ) ) {
					$created_date = JText::sprintf( 'MOD_KONTENTTWEET_MINUTES_AGO', round( $diff / 60 ) );
				} elseif( $diff < ( 90 * 60 ) ) {
					$created_date = JText::_( 'MOD_KONTENTTWEET_ABOUT_AN_HOUR_AGO' );
				} elseif( $diff < ( 24 * 3600 ) ) {
					$created_date = JText::sprintf( 'MOD_KONTENTTWEET_ABOUT_HOURS_AGO', round( $diff / 3600 ) );
				} else {
					$created_date = JHTML::_( 'date', $date->toUnix(), JText::_( 'DATE_FORMAT_LC2' ) );
				}
				?>
				<li class="tweet-date">
					<?php echo $created_date; ?>
				</li>
			<?php endif; ?>
		
			<?php if( $params->get( 'parse-links' ) === '1' ) :
				$rule = '/.*?((?:http|https)(?::\/{2}[\w]+)(?:[\/|\.]?)(?:[^\s"]*))/is';
				preg_match_all( $rule, $tweet->text, $matches );
				foreach( $matches[1] as $url ) :
					$tweet->text = str_replace( $url, '<a href="'. $url .'" rel="external">'. $url .'</a>', $tweet->text );
				endforeach;
			endif; ?>
			
			<li class="tweet-message">
				<?php echo str_replace("&", "&amp;", $tweet->text); ?>
			</li>
	
		</ul>
	</li>

<?php endfor; ?>
</ul>