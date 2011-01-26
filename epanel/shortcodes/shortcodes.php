<?php 

/********* Shortcodes v.1.3 ************/

add_action('wp_print_styles', 'add_shortcodes_stylesheet');
function add_shortcodes_stylesheet() {
	$styleUrl = get_bloginfo('template_directory') . '/epanel/shortcodes/shortcodes.css';
	
	wp_register_style('shortcodesStyleSheets', $styleUrl);
	wp_enqueue_style( 'shortcodesStyleSheets');
}

add_action('wp_head','add_shortcodes_js');
function add_shortcodes_js(){ ?>
	<script type='text/javascript'>
	// <![CDATA[
	
		<!---- et_switcher plugin v2 ---->
		(function($)
		{
			$.fn.et_shortcodes_switcher = function(options)
			{
				var defaults =
				{
				   slides: '>div',
				   activeClass: 'active',
				   linksNav: '',
				   findParent: true, //use parent elements to define active states
				   lengthElement: 'li', //parent element, used only if findParent is set to true
				   useArrows: false,
				   arrowLeft: 'a#prev-arrow',
				   arrowRight: 'a#next-arrow',
				   auto: false,
				   autoSpeed: 5000,
				   slidePadding: '',
				   pauseOnHover: true,
				   fx: 'fade',
				   sliderType: ''
				};

				var options = $.extend(defaults, options);

				return this.each(function()
				{
									
					var slidesContainer = jQuery(this).parent().css('position','relative'),
						$slides = jQuery(this).css({'overflow':'hidden','position':'relative'}),
						$slide = $slides.find(options.slides).css({'opacity':'1','position':'absolute','top':'0px','left':'0px','display':'none'}),
						slidesNum = $slide.length,
						zIndex = slidesNum,
						currentPosition = 1,
						slideHeight = 0,
						$activeSlide,
						$nextSlide;
					
					if (options.fx === 'slide') {
						$slide.css({'opacity':'0','position':'absolute','top':'0px','left':'0px','display':'block'});
					} else {
						$slide.filter(':first').css({'display':'block'});
					}
					
					if (options.slidePadding != '') $slide.css('padding',options.slidePadding);
					
					$slide.each(function(){
						jQuery(this).css('z-index',zIndex).addClass('clearfix');
						if (options.fx === 'slide') zIndex--;
						
						slideH = jQuery(this).innerHeight();
						if (slideH > slideHeight) slideHeight = slideH;
					});
					$slides.css('height', slideHeight);
					$slides.css('width', $slides.width());
									
					var slideWidth = $slide.width(),
						slideOuterWidth = $slide.outerWidth();
					
					$slide.css('width',slideWidth);
					
					$slide.filter(':first').css('opacity','1');
					
					if (options.sliderType != '') {
						if (options.sliderType === 'images') {
							controllersHtml = '<div class="controllers-wrapper"><div class="controllers"><a href="#" class="left-arrow">Previous</a>';
							for ($i=1; $i<=slidesNum; $i++) {
								controllersHtml += '<a class="switch" href="#">'+$i+'</a>';
							}
							controllersHtml += '<a href="#" class="right-arrow">Next</a></div><div class="controllers-right"></div></div>';		
							$controllersWrap = jQuery(controllersHtml).prependTo($slides.parent());
							jQuery('.controllers-wrapper .controllers').css('width', 65 + 18*slidesNum);
						}
						
						var etimage_width = $slide.width();
			
						slidesContainer.css({'width':etimage_width});
						$slides.css({'width':etimage_width});
						
						if (options.sliderType === 'images') {
							slidesContainer.css({'height':$slide.height()});
							$slides.css({'height':$slide.height()});
							
							var controllers_width = $controllersWrap.width(),
							leftPosition = Math.round((etimage_width - controllers_width) / 2);
						
							$controllersWrap.css({left: leftPosition});
						}	
					}
					
					
					if (options.linksNav != '') {
						var linkSwitcher = jQuery(options.linksNav);
						
						var linkSwitcherTab = '';
						if (options.findParent) linkSwitcherTab = linkSwitcher.parent();
						else linkSwitcherTab = linkSwitcher;
						
						if (!linkSwitcherTab.filter('.active').length) linkSwitcherTab.filter(':first').addClass('active');
										
						linkSwitcher.click(function(){
							
							var targetElement;

							if (options.findParent) targetElement = jQuery(this).parent();
							else targetElement = jQuery(this);
							
							var orderNum = targetElement.prevAll(options.lengthElement).length+1;
							
							if (orderNum > currentPosition) gotoSlide(orderNum, 1);
							else gotoSlide(orderNum, -1); 
							
							return false;
						});
					}
					
					
					if (options.useArrows) {
						var $right_arrow = jQuery(options.arrowRight),
							$left_arrow = jQuery(options.arrowLeft);
											
						$right_arrow.click(function(){				
							if (currentPosition === slidesNum) 
								gotoSlide(1,1);
							else 
								gotoSlide(currentPosition+1),1;
							
							if (options.linksNav != '') changeTab();
													
							return false;
						});
						
						$left_arrow.click(function(){
							if (currentPosition === 1)
								gotoSlide(slidesNum,-1);
							else 
								gotoSlide(currentPosition-1,-1);
							
							if (options.linksNav != '') changeTab();
							
							return false;
						});
						
					}
					
									
					function changeTab(){
						if (linkSwitcherTab != '') { 
							linkSwitcherTab.siblings().removeClass('active');
							linkSwitcherTab.filter(':eq('+(currentPosition-1)+')').addClass('active');
						}
					}
					
					function gotoSlide(slideNumber,dir){
						if ($slide.filter(':animated').length) return;
					
						$slide.css('opacity','0');
																			
						$activeSlide = $slide.filter(':eq('+(currentPosition-1)+')').css('opacity','1');
										
						if (currentPosition === slideNumber) return;
										
						$nextSlide = $slide.filter(':eq('+(slideNumber-1)+')').css('opacity','1');
										
						if ((currentPosition > slideNumber || currentPosition === 1) && (dir === -1)) {
							if (options.fx === 'slide') slideBack(500);
							if (options.fx === 'fade') slideFade(500);
						} else {
							if (options.fx === 'slide') slideForward(500);
							if (options.fx === 'fade') slideFade(500);
						}
						
						currentPosition = $nextSlide.prevAll().length + 1;
						
						if (options.linksNav != '') changeTab();
						
						if (typeof interval != 'undefined' && options.auto) {
							clearInterval(interval);
							auto_rotate();
						}
											
						return false;
					}
					
					
					if (options.auto) {
						auto_rotate();
						var pauseSlider = false;
					}
					
					if (options.pauseOnHover) { 				
						slidesContainer.hover(function(){
							pauseSlider = true;
						},function(){
							pauseSlider = false;
						});
					}
					
					function auto_rotate(){
						
						interval = setInterval(function(){
							if (!pauseSlider) { 
								if (currentPosition === slidesNum) 
									gotoSlide(1,1);
								else 
									gotoSlide(currentPosition+1),1;
								
								if (options.linksNav != '') changeTab();
							}
						},options.autoSpeed);
						
					}
					
					function slideForward(speed){
						$nextSlide.css('left',slideOuterWidth+'px');
						$activeSlide.animate({left: '-'+slideOuterWidth},speed);
						$nextSlide.animate({left: 0},speed);
					}
					
					function slideBack(speed){
						$nextSlide.css('left','-'+slideOuterWidth+'px');
						$activeSlide.animate({left: slideOuterWidth},speed);
						$nextSlide.animate({left: 0},speed);
					}
					
					function slideFade(speed){					
						$activeSlide.css({zIndex: slidesNum}).fadeOut(700);
						$nextSlide.css({zIndex: (slidesNum+1)}).fadeIn(700);
					}
					
				});
			} 
		})(jQuery);
		<!---- end et_switcher plugin v2 ---->
		
		/////// Shortcodes Javascript ///////
		
		/// tooltip ///
		
		$et_tooltip = jQuery('.et-tooltip');
		
		$et_tooltip.live('mouseover mouseout', function(event){
			if (event.type == 'mouseover') {
				jQuery(this).find('.et-tooltip-box').animate({ opacity: 'show', bottom: '25px' }, 300);
			} else {
				jQuery(this).find('.et-tooltip-box').animate({ opacity: 'hide', bottom: '35px' }, 300);
			}
		});
		
		/// learn more ///
		
		$et_learn_more = jQuery('.et-learn-more .heading-more');
		
		$et_learn_more.live('click', function() {
			if ( jQuery(this).hasClass('open') ) 
				jQuery(this).removeClass('open');
			else 
				jQuery(this).addClass('open');
				
			jQuery(this).parent('.et-learn-more').find('.learn-more-content').animate({ opacity: 'toggle', height: 'toggle' }, 300);
		});
	
	// ]]>
	</script>
<?php }

function add_simple_buttons(){
	$output = "<script type='text/javascript'>\n
	/* <![CDATA[ */ \n";
	
	$buttons = array();
	
	$buttons[] = array('name' => 'raw',
					'options' => array(
						'display_name' => 'raw',
						'open_tag' => '\n[raw]',
						'close_tag' => '[/raw]\n',
						'key' => ''
					));
	$buttons[] = array('name' => 'one_half',
					'options' => array(
						'display_name' => 'one half',
						'open_tag' => '\n[one_half]',
						'close_tag' => '[/one_half]\n',
						'key' => ''
					));
	$buttons[] = array('name' => 'one_half_last',
					'options' => array(
						'display_name' => 'one half last',
						'open_tag' => '\n[one_half_last]',
						'close_tag' => '[/one_half_last]\n',
						'key' => ''
					));
	$buttons[] = array('name' => 'one_third',
					'options' => array(
						'display_name' => 'one third',
						'open_tag' => '\n[one_third]',
						'close_tag' => '[/one_third]\n',
						'key' => ''
					));
	$buttons[] = array('name' => 'one_third_last',
					'options' => array(
						'display_name' => 'one third last',
						'open_tag' => '\n[one_third_last]',
						'close_tag' => '[/one_third_last]\n',
						'key' => ''
					));
	$buttons[] = array('name' => 'one_fourth',
					'options' => array(
						'display_name' => 'one fourth',
						'open_tag' => '\n[one_fourth]',
						'close_tag' => '[/one_fourth]\n',
						'key' => ''
					));
	$buttons[] = array('name' => 'one_fourth_last',
					'options' => array(
						'display_name' => 'one fourth last',
						'open_tag' => '\n[one_fourth_last]',
						'close_tag' => '[/one_fourth_last]\n',
						'key' => ''
					));
	$buttons[] = array('name' => 'two_third',
					'options' => array(
						'display_name' => 'two third',
						'open_tag' => '\n[two_third]',
						'close_tag' => '[/two_third]\n',
						'key' => ''
					));
	$buttons[] = array('name' => 'two_third_last',
					'options' => array(
						'display_name' => 'two third last',
						'open_tag' => '\n[two_third_last]',
						'close_tag' => '[/two_third_last]\n',
						'key' => ''
					));
	$buttons[] = array('name' => 'three_fourth',
					'options' => array(
						'display_name' => 'three fourth',
						'open_tag' => '\n[three_fourth]',
						'close_tag' => '[/three_fourth]\n',
						'key' => ''
					));
	$buttons[] = array('name' => 'three_fourth_last',
					'options' => array(
						'display_name' => 'three fourth last',
						'open_tag' => '\n[three_fourth_last]',
						'close_tag' => '[/three_fourth_last]\n',
						'key' => ''
					));
	$buttons[] = array('name' => 'three_fourth_last',
					'options' => array(
						'display_name' => 'three fourth last',
						'open_tag' => '\n[three_fourth_last]',
						'close_tag' => '[/three_fourth_last]\n',
						'key' => ''
					));
	$buttons[] = array('name' => 'box',
					'options' => array(
						'display_name' => 'box',
						'open_tag' => '\n[box type="shadow"]',
						'close_tag' => '[/box]\n',
						'key' => ''
					));
	$buttons[] = array('name' => 'tooltip',
					'options' => array(
						'display_name' => 'tooltip',
						'open_tag' => '[tooltip text="Tooltip Text"]',
						'close_tag' => '[/tooltip]',
						'key' => ''
					));
	$buttons[] = array('name' => 'learn_more',
					'options' => array(
						'display_name' => 'learn_more',
						'open_tag' => '\n[learn_more caption="Click here to learn more"]',
						'close_tag' => '[/learn_more]\n',
						'key' => ''
					));	
	$buttons[] = array('name' => 'slider',
					'options' => array(
						'display_name' => 'slider',
						'open_tag' => '\n[slider]',
						'close_tag' => '[/slider]\n',
						'key' => ''
					));
	$buttons[] = array('name' => 'slide',
					'options' => array(
						'display_name' => 'slide',
						'open_tag' => '\n[slide]',
						'close_tag' => '[/slide]\n',
						'key' => ''
					));
	$buttons[] = array('name' => 'button',
					'options' => array(
						'display_name' => 'button',
						'open_tag' => '\n[button link="#"]',
						'close_tag' => '[/button]\n',
						'key' => ''
					));
	$buttons[] = array('name' => 'digg',
					'options' => array(
						'display_name' => 'digg',
						'open_tag' => '\n[digg]',
						'close_tag' => '[/digg]\n',
						'key' => ''
					));
	$buttons[] = array('name' => 'stumble',
					'options' => array(
						'display_name' => 'stumble',
						'open_tag' => '\n[stumble]',
						'close_tag' => '[/stumble]\n',
						'key' => ''
					));
	$buttons[] = array('name' => 'facebook',
					'options' => array(
						'display_name' => 'facebook',
						'open_tag' => '\n[facebook]',
						'close_tag' => '[/facebook]\n',
						'key' => ''
					));
					
	$buttons[] = array('name' => 'buzz',
					'options' => array(
						'display_name' => 'buzz',
						'open_tag' => '\n[buzz]',
						'close_tag' => '[/buzz]\n',
						'key' => ''
					));
					
	$buttons[] = array('name' => 'twitter',
					'options' => array(
						'display_name' => 'twitter',
						'open_tag' => '\n[twitter name="name"]',
						'close_tag' => '[/twitter]\n',
						'key' => ''
					));
					
	$buttons[] = array('name' => 'retweet',
					'options' => array(
						'display_name' => 'retweet',
						'open_tag' => '\n[retweet]',
						'close_tag' => '[/retweet]\n',
						'key' => ''
					));
					
	$buttons[] = array('name' => 'feedburner',
					'options' => array(
						'display_name' => 'feedburner',
						'open_tag' => '\n[feedburner name="name"]',
						'close_tag' => '[/feedburner]\n',
						'key' => ''
					));
	$buttons[] = array('name' => 'protected',
					'options' => array(
						'display_name' => 'protected',
						'open_tag' => '\n[protected]',
						'close_tag' => '[/protected]\n',
						'key' => ''
					));
					
					
	for ($i=0; $i <= (count($buttons)-1); $i++) {
		$output .= "edButtons[edButtons.length] = new edButton('ed_{$buttons[$i]['name']}'
			,'{$buttons[$i]['options']['display_name']}'
			,'{$buttons[$i]['options']['open_tag']}'
			,'{$buttons[$i]['options']['close_tag']}'
			,'{$buttons[$i]['options']['key']}'
		); \n";
	}
	
	$output .= "\n /* ]]> */ \n
	</script>";
	echo $output;
}


add_shortcode('digg', 'et_digg');
function et_digg($atts, $content = null) {		
	$output = "<script type='text/javascript'>
(function() {
var s = document.createElement('SCRIPT'), s1 = document.getElementsByTagName('SCRIPT')[0];
s.type = 'text/javascript';
s.async = true;
s.src = 'http://widgets.digg.com/buttons.js';
s1.parentNode.insertBefore(s, s1);
})();
</script>
<!-- Medium Button -->
<a class='DiggThisButton DiggMedium'></a>";
	
	return $output;
}

add_shortcode('stumble','et_stumble');
function et_stumble($atts, $content = null){
	$output = "<script src='http://www.stumbleupon.com/hostedbadge.php?s=5'></script>";
	return $output;
}

add_shortcode('facebook','et_facebook');
function et_facebook($atts, $content = null){
	$output = "<a name='fb_share' type='button_count' href='http://www.facebook.com/sharer.php'>Share</a><script src='http://static.ak.fbcdn.net/connect.php/js/FB.Share' type='text/javascript'></script>";
	return $output;
}

add_shortcode('buzz','et_buzz');
function et_buzz($atts, $content = null){
	$output = "<a title='Post to Google Buzz' class='google-buzz-button' href='http://www.google.com/buzz/post' data-button-style='normal-count'></a>
<script type='text/javascript' src='http://www.google.com/buzz/api/button.js'></script>";
	return $output;
}

add_shortcode('twitter','et_twitter');
function et_twitter($atts, $content = null){
	extract(shortcode_atts(array(
		"name" => 'name'
	), $atts));
	$output = "<script type='text/javascript' src='http://twittercounter.com/embed/{$name}/ffffff/111111'></script>";
	return $output;
}

add_shortcode('feedburner','et_feedburner');
function et_feedburner($atts, $content = null){
	extract(shortcode_atts(array(
		"name" => 'name'
	), $atts));
	$output = "<a href='http://feeds.feedburner.com/{$name}'><img src='http://feeds.feedburner.com/~fc/{$name}?bg=99CCFF&amp;fg=444444&amp;anim=0' height='26' width='88' style='border:0' alt='' />
</a>";
	return $output;
}


add_shortcode('retweet','et_retweet');
function et_retweet($atts, $content = null){
	$output = "<a href='http://twitter.com/share' class='twitter-share-button' data-count='vertical'>Tweet</a><script type='text/javascript' src='http://platform.twitter.com/widgets.js'></script>";
	return $output;
}


add_shortcode('protected','et_protected');
function et_protected($atts, $content = null){

	if ( is_user_logged_in() ) {
		$content = et_delete_htmltags($content);	
		$content = do_shortcode($content);
		$output = $content;
	} else {
		$output = "<div class='et-protected'>
					<div class='et-protected-form'>
					<form action='" . get_option('home') . "/wp-login.php' method='post'>
						<p><label>Username: <input type='text' name='log' id='log' value='" . wp_specialchars(stripslashes($user_login), 1) . "' size='20' /></label></p>
						<p><label>Password: <input type='password' name='pwd' id='pwd' size='20' /></label></p>
						<input type='submit' name='submit' value='Login' class='etlogin-button' />
					</form> 
					</div> <!-- .et-protected-form -->
				<p class='et-registration'>Not a member? <a href='".site_url('wp-login.php?action=register', 'login_post')."'>Register today!</a></p>
				</div> <!-- .et-protected -->";
	}
				
	return $output;
}


add_shortcode('box', 'et_box');
function et_box($atts, $content = null) {
	extract(shortcode_atts(array(
				"type" => 'shadow'
			), $atts));
	$content = et_delete_htmltags($content);
		
	$output = "<div class='et-box et-{$type}'>
					<div class='et-box-content'>";
	$output .= do_shortcode($content);
	$output .= "</div></div>";
	
	return $output;
}

add_shortcode('tooltip', 'et_tooltip');
function et_tooltip($atts, $content = null) {
	extract(shortcode_atts(array(
				"text" => 'Add a Tooltip Text'
			), $atts));

	$content = et_delete_htmltags($content);
			
	$content = do_shortcode($content);
			
	$output = "<span class='et-tooltip'>{$content}<span class='et-tooltip-box'>{$text}<span class='et-tooltip-arrow'></span></span></span>";
	
	return $output;
}

add_shortcode('learn_more', 'et_learnmore');
function et_learnmore($atts, $content = null) {
	extract(shortcode_atts(array(
				"caption" => 'Click here to learn more',
				"state" => 'close'
			), $atts));

	$content = et_delete_htmltags($content);
			
	$content = do_shortcode($content);

	$divClass = ($state == 'close') ? 'et-learn-more' : 'et-learn-more et-open';
	$hClass = ($state == 'close') ? 'heading-more' : 'heading-more open';
	
	$output = "<div class='{$divClass}'>
					<h3 class='{$hClass}'><span>{$caption}</span></h3>
					<div class='learn-more-content'>{$content}</div>
				</div>";
	
	return $output;
}

add_shortcode('button', 'et_button');
function et_button($atts, $content = null) {
	extract(shortcode_atts(array(
				"link" => "#",
				"color" => "blue",
				"type" => "small",
				"icon" => "download"
			), $atts));
	
	$output = '';
	
	$content = et_delete_htmltags($content);
	$content = do_shortcode($content);

	if ($type == 'small')
		$output .= "<a href='{$link}' class='small-button small{$color}'><span>{$content}</span></a>";
	
	if ($type == 'big')
		$output .= "<a href='{$link}' class='big-button big{$color}'><span>{$content}</span></a>";
		
	if ($type == 'icon')
		$output .= "<a href='{$link}' class='icon-button {$icon}-icon'><span class='et-icon'><span>{$content}</span></span></a>";
	
	$output .= '<div class="clear"></div>';
		
	return $output;
}

add_shortcode('slide', 'et_slide');
function et_slide($atts, $content = null) {
	
	$content = et_delete_htmltags($content);
	
	$content = do_shortcode($content);
	
	$output = "<div class='et-slide'>
					{$content}
				</div>";
	
	return $output;
}

add_shortcode('tabs', 'et_tabs');
function et_tabs($atts, $content = null) {
	extract(shortcode_atts(array(
				"fx" => 'fade',
				"auto" => 'no',
				"autoSpeed" => '5000',
				"id" => '1',
				"slidertype" => 'top tabs'
			), $atts));
	
	$i = rand(0, 1000);
	
	$auto = ( $auto == 'no' ) ? 'false' : 'true';
	
	$content = et_delete_htmltags($content);
	
	$content = do_shortcode($content);
	
	if ($slidertype == 'top tabs') {	
		$output = "
			<div class='et-tabs-container' id='et-tabs-container{$i}'>
				{$content}
			</div> <!-- .et-tabs-container -->
			<script type='text/javascript'>
				//jQuery(document).ready(function() {
					jQuery('#et-tabs-container{$i} .et-tabs-content').et_shortcodes_switcher({slidePadding: '20px 25px 8px', linksNav: '#et-tabs-container{$i} .et-tabs-control li a', findParent: true, fx: '{$fx}', auto: {$auto}, autoSpeed: '{$autoSpeed}'});
				//});
			</script>";
	} elseif ($slidertype == 'left tabs') {
		$output = "
			<div class='tabs-left' id='tabs-left{$i}'>
				{$content}
			</div> <!-- .tabs-left -->
			<script type='text/javascript'>
				//jQuery(document).ready(function() {
					 jQuery('#tabs-left{$i} .et-tabs-content').et_shortcodes_switcher({linksNav: '#tabs-left{$i} .et-tabs-control li a', findParent: true, fx: '{$fx}', auto: {$auto}, autoSpeed: '{$autoSpeed}'});
				//});
			</script>";
	} elseif ($slidertype == 'simple') {
		$output = "
		<div class='et-simple-slider' id='et-simple-slider{$i}'>
			<a href='#' class='et-slider-leftarrow'>Left</a>
			<a href='#' class='et-slider-rightarrow'>Right</a>
			<div class='et-simple-slides'>
				{$content}
			</div>
		</div> <!-- .et-simple-slider -->
		<script type='text/javascript'>
			//jQuery(document).ready(function() {
				jQuery('#et-simple-slider{$i} .et-simple-slides').et_shortcodes_switcher({sliderType: 'simple', auto: {$auto}, autoSpeed: '{$autoSpeed}',useArrows: true, fx: '{$fx}', arrowLeft: '#et-simple-slider{$i} a.et-slider-leftarrow', arrowRight: '#et-simple-slider{$i} a.et-slider-rightarrow'});
			//});
		</script>";
	} elseif ($slidertype == 'images') {
		$output = "
		<div class='et-image-slider' id='et-image-slider{$i}'>
			<div class='et-image-slides'>
				{$content}
			</div>
			
			<div class='et-image-shadow'></div>
			<div class='et-image-shadowleft'></div>
			<div class='et-image-shadowright'></div>
		</div> <!-- .et-image-slider -->
		<script type='text/javascript'>
			//jQuery(document).ready(function() {
				 jQuery('#et-image-slider{$i} .et-image-slides').et_shortcodes_switcher({sliderType: 'images', auto: {$auto}, autoSpeed: '{$autoSpeed}',useArrows: true, fx: '{$fx}', arrowLeft: '#et-image-slider{$i} a.left-arrow', arrowRight: '#et-image-slider{$i} a.right-arrow', linksNav: '#et-image-slider{$i} .controllers a.switch',findParent: false, lengthElement: 'a.switch'});
			//});
		</script>";
	}
		
	return $output;
}

add_shortcode('tabcontainer', 'et_tabcontainer');
function et_tabcontainer($atts, $content = null) {
	
	$content = et_delete_htmltags($content);
	$content = do_shortcode($content);
	
	$output = "
		<ul class='et-tabs-control'>
			{$content}
		</ul> <!-- .et-tabs-control -->";
		
	return $output;
}

add_shortcode('imagetabcontainer', 'et_imagetabcontainer');
function et_imagetabcontainer($atts, $content = null) {
	
	$content = et_delete_htmltags($content);
	
	$content = do_shortcode($content);
	
	$output = "
		<div class='controllers-wrapper'>
			<div class='controllers'>
				<a class='left-arrow' href='#'>Previous</a>
				{$content}
				<a class='right-arrow' href='#'>Next</a>
			</div> <!-- end #controllers -->
			<div class='controllers-right'></div>
		</div><!-- end #controllers-wrapper -->";
		
	return $output;
}

add_shortcode('imagetabtext', 'et_imagetabtext');
function et_imagetabtext($atts, $content = null) {	
	
	$content = et_delete_htmltags($content);
	
	$content = do_shortcode($content);
		
	$output = "
		<a href='#' class='switch'>
			{$content}
		</a>";
		
	return $output;
}

add_shortcode('tabtext', 'et_tabtext');
function et_tabtext($atts, $content = null) {	
	
	$content = et_delete_htmltags($content);
	
	$content = do_shortcode($content);
		
	$output = "
		<li><a href='#'>
			{$content}
		</a></li>";
		
	return $output;
}

add_shortcode('tabcontent', 'et_tabcontent');
function et_tabcontent($atts, $content = null) {
	$content = et_delete_htmltags($content);
	
	$content = do_shortcode($content);
	
	$output = "
		<div class='et-tabs-content'>
			{$content}
		</div>";
	
	return $output;
}

add_shortcode('tab', 'et_tab');
function et_tab($atts, $content = null) {
	$content = et_delete_htmltags($content);

	$content = do_shortcode($content);
	
	$output = "
		<div class='et_slidecontent'>
			{$content}
		</div>";
		
	return $output;
}

add_shortcode('imagetab', 'et_imagetab');
function et_imagetab($atts, $content = null) {
	extract(shortcode_atts(array(
				"width" => '',
				"height" => ''
			), $atts));
	
	$content = et_delete_htmltags($content);

	$content = do_shortcode($content);
	
	$output = "
		<div class='et-image' style='background: url(".get_bloginfo('template_directory') . "/timthumb.php?src={$content}&amp;w={$width}&amp;h={$height}&amp;zc=1) no-repeat; width: {$width}px; height: {$height}px;'><span class='et-image-overlay'> </span></div>";
		
	return $output;
}

add_shortcode('author', 'et_author');
function et_author($atts, $content = null) {
	
	$content = et_delete_htmltags($content);

	$content = do_shortcode($content);
	
	$output = "
		<div class='author-shortcodes'>
			<div class='author-inner'>
				{$content}
			</div> <!-- .author-inner -->
		</div> <!-- .author-shortcodes -->";
		
	return $output;
}

add_shortcode('author_image', 'et_author_image');
function et_author_image($atts, $content = null) {
	extract(shortcode_atts(array(
		"timthumb" => 'on'
	), $atts));

	$content = et_delete_htmltags($content);
	
	$src = ($timthumb == 'on') ? (get_bloginfo('template_directory') . "/timthumb.php?src={$content}&amp;w=57&amp;h=57&amp;zc=1") : $content;

	$content = do_shortcode($content);
	
	$output = "
		<div class='author-image'>
			<img src='{$src}' alt='' />
			<div class='author-overlay'></div>
		</div> <!-- .author-image -->";
		
	return $output;
}

add_shortcode('author_info', 'et_author_info');
function et_author_info($atts, $content = null) {
	
	$content = et_delete_htmltags($content);
	
	$content = do_shortcode($content);
	
	$output = "
		<div class='author-info'>
			{$content}
		</div> <!-- .author-info -->";
		
	return $output;
}


add_shortcode('one_half', 'et_columns');
add_shortcode('one_half_last', 'et_columns');
add_shortcode('one_third', 'et_columns');
add_shortcode('one_third_last', 'et_columns');
add_shortcode('one_fourth', 'et_columns');
add_shortcode('one_fourth_last', 'et_columns');
add_shortcode('two_third', 'et_columns');
add_shortcode('two_third_last', 'et_columns');
add_shortcode('three_fourth', 'et_columns');
add_shortcode('three_fourth_last', 'et_columns');
function et_columns($atts, $content = null, $name='') {
	
	$content = do_shortcode($content);
	
	$pos = strpos($name,'_last');	

	if($pos !== false)
		$name = str_replace('_last',' last',$name);
	
	$output = "<div class='{$name}'>
					{$content}
				</div>";
	if($pos !== false) 
		$output .= "<div class='clear'></div>";
	
	return $output;
}

function et_delete_htmltags($content){
	$content = preg_replace('#<br />#', '', $content);	
	$content = preg_replace('#^<\/p>|<p>$#', '', $content);
	$content = preg_replace('#(<p>)(\s*\[[\w\s\]\[\/]*)(</p>)#', '$2', $content);
	
	return $content;
}


add_action('admin_init', 'action_admin_init');
function action_admin_init(){
	if ( current_user_can( 'edit_posts' ) && current_user_can( 'edit_pages' ) ) {
		if ( in_array(basename($_SERVER['PHP_SELF']), array('post-new.php', 'page-new.php', 'post.php', 'page.php') ) ) {
			add_filter('mce_buttons', 'filter_mce_button');
			add_filter('mce_external_plugins', 'filter_mce_plugin');
			add_action('admin_head','add_simple_buttons');
			add_action('edit_form_advanced', 'advanced_buttons');
			add_action('edit_page_form', 'advanced_buttons');
		}
	}
}

function filter_mce_button($buttons) {
	array_push( $buttons, '|', 'et_learn_more', 'et_box', 'et_button', 'et_tabs', 'et_author' );

	return $buttons;
}

function filter_mce_plugin($plugins) {	
	$plugins['et_quicktags'] = get_bloginfo('template_url'). '/epanel/shortcodes/js/editor_plugin.js';
	
	return $plugins;
}

function advanced_buttons(){ ?>
	<script type="text/javascript">
		var defaultSettings = {},
			outputOptions = '',
			selected ='',
			content = '';
		
		defaultSettings['learn_more'] = {
			caption: {
				name: 'Caption',
				defaultvalue: 'Caption goes here',
				description: 'Description goes here',
				type: 'text'
			},
			state: {
				name: 'State',
				defaultvalue: 'close',
				description: 'Description goes here',
				type: 'select',
				options: 'open|close'
			},
			content: {
				name: 'Content',
				defaultvalue: 'Content goes here',
				description: 'Description goes here',
				type: 'textarea'
			}
		};
		
		defaultSettings['box'] = {
			type: {
				name: 'Type',
				defaultvalue: 'shadow',
				description: 'Description goes here',
				type: 'select',
				options: 'info|warning|download|bio|shadow'
			},
			content: {
				name: 'Content',
				defaultvalue: 'Content goes here',
				description: 'Description goes here',
				type: 'textarea'
			}
		};
		
		defaultSettings['button'] = {
			link: {
				name: 'Link',
				defaultvalue: '#',
				description: 'Description goes here',
				type: 'text'
			},
			type: {
				name: 'Type',
				defaultvalue: 'small',
				description: 'Description goes here',
				type: 'select',
				options: 'small|big|icon'
			},
			color: {
				name: 'Color',
				defaultvalue: 'blue',
				description: 'Description goes here',
				type: 'select',
				options: 'blue|lightblue|teal|silver|black|pink|purple|orange|green|red'
			},
			content: {
				name: 'Content',
				defaultvalue: 'Link text',
				description: 'Description goes here',
				type: 'textarea'
			},
			icon: {
				name: 'Icon',
				defaultvalue: 'download',
				description: 'Used for icon button type',
				type: 'select',
				options: 'download|search|refresh|question|people|warning|mail|heart|paper|notice|stats|rss'
			}
		};
		
		defaultSettings['tabs'] = {
			slidertype: {
				name: 'Slider Type',
				defaultvalue: 'fade',
				description: 'Description goes here',
				type: 'select',
				options: 'top tabs|left tabs|simple|images'
			},
			fx: {
				name: 'Effect',
				defaultvalue: 'fade',
				description: 'Description goes here',
				type: 'select',
				options: 'fade|slide'
			},
			auto: {
				name: 'Auto',
				defaultvalue: 'no',
				description: 'Description goes here',
				type: 'select',
				options: 'no|yes'
			},
			autospeed: {
				name: 'Auto Speed',
				defaultvalue: '5000',
				description: 'Description goes here',
				type: 'text'
			},
			tabtext: {
				name: 'Tab Text',
				defaultvalue: '',
				description: 'Description goes here',
				type: 'text',
				clone: 'cloned'
			},
			tabcontent: {
				name: 'Tab Content',
				defaultvalue: 'Content goes here',
				description: 'Description goes here',
				type: 'textarea',
				clone: 'cloned'
			}
		}
		
		defaultSettings['author'] = {
			imageurl: {
				name: 'Image Url',
				defaultvalue: '',
				description: 'Description goes here',
				type: 'text'
			},
			timthumb: {
				name: 'Use timthumb resizing',
				defaultvalue: 'on',
				description: 'Description goes here',
				type: 'select',
				options: 'on|off'
			},
			content: {
				name: 'Content',
				defaultvalue: 'Content goes here',
				description: 'Description goes here',
				type: 'textarea'
			}
		}
		
		function CustomButtonClick(tag){
			
			var index = tag;
			
				for (var index2 in defaultSettings[index]) {
					if (defaultSettings[index][index2]['clone'] === 'cloned')
						outputOptions += '<tr class="cloned">\n';
					else if (index === 'button' && index2 === 'icon')
						outputOptions += '<tr class="hidden">\n';
					else
						outputOptions += '<tr>\n';
					outputOptions += '<th><label for="et-' + index2 + '">'+ defaultSettings[index][index2]['name'] +'</label></th>\n';
					outputOptions += '<td>';
					
					if (defaultSettings[index][index2]['type'] === 'select') {
						var optionsArray = defaultSettings[index][index2]['options'].split('|');
						
						outputOptions += '\n<select name="et-'+index2+'" id="et-'+index2+'">\n';
						
						for (var index3 in optionsArray) {
							selected = (optionsArray[index3] === defaultSettings[index][index2]['defaultvalue']) ? ' selected="selected"' : '';
							outputOptions += '<option value="'+optionsArray[index3]+'"'+ selected +'>'+optionsArray[index3]+'</option>\n';
						}
						
						outputOptions += '</select>\n';
					}
					
					if (defaultSettings[index][index2]['type'] === 'text') {
						cloned = '';
						if (defaultSettings[index][index2]['clone'] === 'cloned') cloned = "[]";
						outputOptions += '\n<input type="text" name="et-'+index2+cloned+'" id="et-'+index2+'" value="'+defaultSettings[index][index2]['defaultvalue']+'" />\n';
					}
					
					if (defaultSettings[index][index2]['type'] === 'textarea') {
						cloned = '';
						if (defaultSettings[index][index2]['clone'] === 'cloned') cloned = "[]";
						outputOptions += '<textarea name="et-'+index2+cloned+'" id="et-'+index2+'" cols="40" rows="10">'+defaultSettings[index][index2]['defaultvalue']+'</textarea>';
					}
					
					outputOptions += '\n<br/><small>'+ defaultSettings[index][index2]['description'] +'</small>';
					outputOptions += '\n</td>';
					
				}
			
		
			var width = jQuery(window).width(),
				tbHeight = jQuery(window).height(),
				tbWidth = ( 720 < width ) ? 720 : width;
			
			tbWidth = tbWidth - 80;
			tbHeight = tbHeight - 84;

			var tbOptions = "<div id='et_shortcodes_div'><form id='et_shortcodes'><table id='shortcodes_table' class='form-table et-"+ tag +"'>";
			tbOptions += outputOptions;
			tbOptions += '</table>\n<p class="submit">\n<input type="button" id="shortcodes-submit" class="button-primary" value="Ok" name="submit" /></p>\n</form></div>';
			
			var form = jQuery(tbOptions);
			
			var table = form.find('table');
			form.appendTo('body').hide();
			
			
			if (tag === 'tabs') {
				$moreTabs = jQuery('<p><a href="#" id="et_add_more_tabs">+ Add One More Tab</a></p>').appendTo('form#et_shortcodes tbody');
				$moreTabsLink = jQuery('a#et_add_more_tabs');
				
				$moreTabsLink.live('click',function() {
					var clonedElements = jQuery('form#et_shortcodes .cloned');
										
					newElements = clonedElements.slice(0,2).clone();
								
					var cloneNumber = clonedElements.length,
						labelNum = cloneNumber / 2;
					
					newElements.each(function(index){
						if ( index === 0 ) jQuery(this).css({'border-top':'1px solid #eeeeee'});
						
						var label = jQuery(this).find('label').attr('for'),
							newLabel = label + labelNum;
					
						jQuery(this).find('label').attr('for',newLabel);
						jQuery(this).find('input, textarea').attr('id',newLabel);
					});
					
					newElements.appendTo('form#et_shortcodes tbody');
					$moreTabs.appendTo('form#et_shortcodes tbody');
					return false;
				});		
			}
			
			
			form.find('#shortcodes-submit').click(function(){
							
				var shortcode = '['+tag;
								
				for( var index in defaultSettings[tag]) {
					var value = table.find('#et-' + index).val();
					if (index === 'content') { 
						content = value;
						continue;
					}
					
					if (defaultSettings[tag][index]['clone'] !== undefined) {
						content = 'cloned';
						continue;
					} 
					
					if ( value !== defaultSettings[tag][index]['defaultvalue'] )
						shortcode += ' ' + index + '="' + value + '"';
						
				}
				
				var $et_slidertype = jQuery('#et-slidertype').val();
				
				shortcode += ']';
				
				if (content != '') {
					
					if (tag === 'tabs') {
					
						var $et_form = jQuery('form#et_shortcodes'),
							tabsOutput = '',
							$et_slidertype = jQuery('#et-slidertype').val();
						
						if ($et_slidertype === 'images') {
							prefix = 'image';
							dimensions = ' width="' + jQuery('#et-imagewidth').val() + '"'+' height="' + jQuery('#et-imageheight').val() + '"';
						} else {
							prefix = '';
							dimensions = '';
						}
						
						tabsOutput += '['+prefix+'tabcontainer]\n';
						$et_form.find("input[name='et-tabtext[]']").each(function(){
							tabsOutput += '['+prefix+'tabtext]'+jQuery(this).val()+'[/'+prefix+'tabtext]\n';
						});
						tabsOutput += '[/'+prefix+'tabcontainer]\n';						
						
						if ($et_slidertype === 'simple' || $et_slidertype === 'images') tabsOutput = '';
						
						if ($et_slidertype != 'simple' && $et_slidertype != 'images') tabsOutput += '[tabcontent]\n';
						$et_form.find("textarea[name='et-tabcontent[]']").each(function(){
							tabsOutput += '['+prefix+'tab'+dimensions+']'+jQuery(this).val()+'[/'+prefix+'tab]'+"\n";
						});
						
						if ($et_slidertype != 'simple' && $et_slidertype != 'images') tabsOutput += '[/tabcontent]\n';
						
						content = tabsOutput;
					}
					
					if (tag === 'author') {
						var $et_form = jQuery('form#et_shortcodes');
						
						imageurl = $et_form.find('#et-imageurl').val();
						timthumb = $et_form.find('#et-timthumb').val();
						content = $et_form.find('#et-content').val();
						
						shortcode = "[author]\n[author_image timthumb='"+timthumb+"']"+imageurl+"[/author_image]\n[author_info]"+content+"[/author_info]\n";
						content = '';
					}
				
					shortcode += content;
					shortcode += '[/'+tag+'] ';
				}

				tinyMCE.activeEditor.execCommand('mceInsertContent', 0, shortcode);
				
				tb_remove();
			});
			
			tb_show( 'ET ' + tag + ' Shortcode', '#TB_inline?width=' + tbWidth + '&height=' + tbHeight + '&inlineId=et_shortcodes_div' );
			jQuery('#et_shortcodes_div').remove();
			outputOptions = '';
		}
		
		jQuery(document).ready(function(){
			var buttonTypeField = jQuery('table.et-button select#et-type');
						
			buttonTypeField.live('change',function() {
				var optionsSmallButton = ['blue','lightblue','teal','silver','black','pink','purple','orange','green','red'],
					optionsBigButton = ['blue','purple','orange','green','red','turquoise'],
					options = '';
				
				if (jQuery(this).val() === 'big') {
					for (var i = 0; i < optionsBigButton.length; i++) {
						options += '<option value="' + optionsBigButton[i] + '">' + optionsBigButton[i] + '</option>';
					}
					
					if (!jQuery('select#et-icon').parents('tr.hidden').length) jQuery('select#et-icon').parents('tr').addClass('hidden');
					if (jQuery('select#et-color').parents('tr.hidden').length) jQuery('select#et-color').parents('tr').removeClass('hidden');
				}
				
				if (jQuery(this).val() === 'small') {
					for (var i = 0; i < optionsSmallButton.length; i++) {
						options += '<option value="' + optionsSmallButton[i] + '">' + optionsSmallButton[i] + '</option>';
					}
					if (!jQuery('select#et-icon').parents('tr.hidden').length) jQuery('select#et-icon').parents('tr').addClass('hidden');
					if (jQuery('select#et-color').parents('tr.hidden').length) jQuery('select#et-color').parents('tr').removeClass('hidden');
				}
				
				if (jQuery(this).val() === 'icon') {
					if (jQuery('select#et-icon').parents('tr.hidden').length) jQuery('select#et-icon').parents('tr').removeClass('hidden');
					
					if (!jQuery('select#et-color').parents('tr.hidden').length) jQuery('select#et-color').parents('tr').addClass('hidden');
				}
				
				if (options !== '') jQuery(this).parents('tbody').find('select#et-color').html(options);
			});
			
			var tabTypeField = jQuery('table.et-tabs select#et-slidertype');
			tabTypeField.live('change',function() {
				if (jQuery(this).val() === 'images') {
					if (!jQuery('.et-tabs #et-imagewidth').length) { 
						$heightImage = jQuery('<tr><th><label for="et-imageheight">Image Height</label></th><td><input type="text" value="" id="et-imageheight" name="et-imageheight"><br><small>Description goes here</small></td></tr>').prependTo('form#et_shortcodes tbody');
						$widthImage = jQuery('<tr><th><label for="et-imagewidth">Image Width</label></th><td><input type="text" value="" id="et-imagewidth" name="et-imagewidth"><br><small>Description goes here</small></td></tr>').prependTo('form#et_shortcodes tbody');
					}
					
					if (typeof $heightImage != 'undefined') $heightImage.show();
					if (typeof $widthImage != 'undefined') $widthImage.show();
					
					jQuery('input[name^="et-tabtext"]').parents('tr.cloned').hide(); //hide tab text
				} else {
					if (typeof $heightImage != 'undefined') $heightImage.hide();
					if (typeof $widthImage != 'undefined') $widthImage.hide();
					
					if(jQuery(this).val() != 'simple') jQuery('input[name^="et-tabtext"]').parents('tr.cloned:hidden').show(); //show tab text
					else jQuery('input[name^="et-tabtext"]').parents('tr.cloned').hide();
				}
			});
		});
	</script>
<?php } ?>