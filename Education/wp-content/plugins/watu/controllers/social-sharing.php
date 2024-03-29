<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class WatuSharing {
	static function options() {
		global $wpdb;
		if(!empty($_POST['ok']) and check_admin_referer('watu_social_sharing')) {
			$fb_app_id = sanitize_text_field($_POST['facebook_appid']);
			$linkedin_enabled = empty($_POST['linkedin_enabled']) ? 0 : 1;
			$linkedin_title = sanitize_text_field($_POST['linkedin_title']);
			$use_twitter = empty($_POST['use_twitter']) ? 0 : 1;
			$show_count = empty($_POST['show_count']) ? 0 : 1;
			$via = sanitize_text_field($_POST['via']);			
			$hashtag = sanitize_text_field($_POST['hashtag']);
			
			update_option('watuproshare_facebook_appid', $fb_app_id);
			$linkedin_options = array("enabled" => $linkedin_enabled,  "msg"=>esc_sql($_POST['linkedin_msg']), 'title' => $linkedin_title);
			update_option('watuproshare_linkedin', $linkedin_options);	
			$twitter_options = array("use_twitter" => $use_twitter, "show_count" => $show_count,
			 "via"=>$_POST['via'], "hashtag" => $_POST['hashtag'], 'large_button' => '',
			 "tweet"=>esc_sql($_POST['tweet']));
			update_option('watuproshare_twitter', $twitter_options);
		}
		
		$appid = get_option('watuproshare_facebook_appid');	
		$linkedin_options = get_option('watuproshare_linkedin');
		$twitter_options = get_option('watuproshare_twitter');
		if(@file_exists(get_stylesheet_directory().'/watu/sharing-options.html.php')) include get_stylesheet_directory().'/watu/sharing-options.html.php';
		else include(WATU_PATH . '/views/sharing-options.html.php');  
	}	
	
	// display the social sharing buttons
	static function display() {
		global $wpdb;
		$taking_id = $GLOBALS['watu_taking_id'];	
		ob_start();
		// https://developers.facebook.com/docs/sharing/reference/feed-dialog
		$appid = get_option('watuproshare_facebook_appid');
		
		// get the grade title and description
		$grade_id = $wpdb->get_var($wpdb->prepare("SELECT grade_id FROM ".WATU_TAKINGS." WHERE ID=%d", $taking_id));
		if(empty($grade_id)) $grade = (object)array("gtitle"=>'None', 'gdescription'=>'None');
		else $grade = $wpdb->get_row($wpdb->prepare("SELECT * FROM ".WATU_GRADES." WHERE ID=%d", $grade_id));
		
		// select quiz name
		$quiz_name = $wpdb->get_var($wpdb->prepare("SELECT tE.name FROM ".WATU_EXAMS." tE
			JOIN ".WATU_TAKINGS." tT ON tE.ID = tT.exam_id 
			WHERE tT.ID = %d", $taking_id));
			
		// keep linkedin vars always because they are also used in Facebook
		$linkedin = get_option('watuproshare_linkedin');
		$linkedin_msg = stripslashes($linkedin['msg']);
		$linkedin_title = stripslashes($linkedin['title']);		
				
		// title and description set up?
		if(!empty($linkedin_title)) {
			$linkedin_title = str_replace('{{{grade-title}}}', stripslashes($grade->gtitle), $linkedin_title);				
			$linkedin_title = str_replace('{{{quiz-name}}}', stripslashes($quiz_name), $linkedin_title);
		}
		if(!empty($linkedin_msg)) {
			$linkedin_msg = str_replace('{{{grade-title}}}', stripslashes($grade->gtitle), $linkedin_msg);			
			$linkedin_msg = str_replace('{{{grade-description}}}', stripslashes($grade->gdescription), $linkedin_msg);	
			$linkedin_msg = str_replace('{{{quiz-name}}}', stripslashes($quiz_name), $linkedin_msg);
			$linkedin_msg = str_replace('{{{url}}}', get_permalink($_POST['post_id']), $linkedin_msg);
		}
		
		// if not, default to grade title and desc
		if(empty($linkedin_title)) $linkedin_title = $grade->gtitle;
		if(empty($linkedin_msg)) $linkedin_msg = $grade->gdescription;
		
		$linkedin_title = stripslashes($linkedin_title);
		$linkedin_msg = stripslashes($linkedin_msg);	
		
		// any picture?
		$picture_str = '';
		if(strstr($grade->gdescription, '<img')) {
			// find all pictures in the grade descrption
			$html = stripslashes($grade->gdescription);
			$dom = new DOMDocument;
			$dom->loadHTML($html);
			$images = array();
			foreach ($dom->getElementsByTagName('img') as $image) {
			    $src =  $image->getAttribute('src');	
			    $class = $image->getAttribute('class');
			    $images[] = array('src'=>$src, 'class'=>$class);
			} // end foreach DOM element
			
			if(sizeof($images)) {
				$target_image = $images[0]['src'];
				
				// but check if we have any that are marked with the class
				foreach($images as $image) {
					if(strstr($image['class'], 'watu-share')) {
						$target_image = $image['src'];
						break;
					}
				}
				
				$picture_str = "&picture=".urlencode($target_image);
			}
		}   // end searching for image
		
		$twitter_options = get_option('watuproshare_twitter');
		
		// prepare tweet text
		if(!empty($twitter_options['use_twitter'])) {
			$tweet = stripslashes($twitter_options['tweet']);
			
			if(empty($tweet)) {
				$tweet = stripslashes($grade->gdescription);
				if(empty($tweet)) $tweet = stripslashes($grade->gtitle);
			}
			else {
				$tweet = str_replace('{{{grade-title}}}', stripslashes($grade->gtitle), $tweet);
				$tweet = str_replace('{{{grade-description}}}', stripslashes($grade->gdescription), $tweet);
				$tweet = str_replace('{{{quiz-name}}}', stripslashes($quiz_name), $tweet);
			}
			
			$tweet = substr($tweet, 0, 140);
		}
		
		
		?>	
		<div><?php if(!empty($appid)):?><a title="<?php _e('Share your results on Facebook', 'watu');?>" onclick="return !window.open(this.href, 'Facebook', 'width=640,height=300')" href="https://www.facebook.com/dialog/feed?app_id=<?php echo $appid?>&amp;display=popup&amp;link=<?php echo urlencode(get_permalink($_POST['post_id']))?>&amp;name=<?php echo urlencode($linkedin_title)?>&amp;redirect_uri=<?php echo urlencode(get_permalink($_POST['post_id']))?>&amp;description=<?php echo urlencode($linkedin_msg)?><?php echo $picture_str?>" target="_blank"><img src="<?php echo WATU_URL.'/img/share/facebook.png'?>"></a>&nbsp;
		<?php endif; // end if Facebook 
		 if(!empty($twitter_options['use_twitter'])):?>
		 <a href="https://twitter.com/share" class="twitter-share-button watu-twitter-share-button" data-url="<?php echo get_permalink($_POST['post_id'])?>" data-via="<?php echo $twitter_options['via']?>" data-hashtags="<?php echo $twitter_options['hashtag']?>" data-text="<?php echo htmlentities($tweet)?>" <?php if(empty($twitter_options['show_count'])):?>data-count="none"<?php endif;?>>Tweet</a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
	   <?php endif;?></div>
		<?php 
		$content = ob_get_clean();
		return $content;
	}
}