<?php
/*
Template Name: Single Auction
*/
/**
 * This the default template for displaying a single Prospress post.
 * It includes all the basic elements for a Prospress post in a very 
 * neutral style.
 */

global $market_systems;

$market = $market_systems[ 'auctions' ];

wp_enqueue_style( 'prospress',  PP_CORE_URL . '/prospress.css' );
wp_enqueue_style('thickbox');
wp_enqueue_script('jquery'); 
wp_enqueue_script('thickbox'); 
?>

<?php the_post(); ?>

<?php get_header(); ?>

<?php include(TEMPLATEPATH . '/includes/breadcrumbs.php'); ?>
<div id="main-area">
	<div id="main-content" class="clearfix">
		<div id="left-column">
			<?php if (get_option('estore_integration_single_top') <> '' && get_option('estore_integrate_singletop_enable') == 'on') echo(get_option('estore_integration_single_top')); ?>
	
			<div class="post clearfix">				
				
				<?php $custom = get_post_custom($post->ID);
				$et_price = isset($custom["price"][0]) ? $custom["price"][0] : '';
				if ($et_price <> '') $et_price = get_option('estore_cur_sign') . $et_price;
				$et_description = isset($custom["description"][0]) ? $custom["description"][0] : '';
				$et_band =  isset($custom["et_band"][0]) ? $custom["et_band"][0] : '';

				$custom["thumbs"] = unserialize($custom["thumbs"][0]); ?>

				<?php if ($et_band <> '') { ?>
					<span class="band<?php echo(' '.$et_band); ?>"></span>
				<?php }; ?>

				<?php if (!empty($custom["thumbs"])) { ?>
					<div id="product-slider">
						<div id="product-slides">
							<?php for ($i = 0; $i <= count($custom["thumbs"])-1; $i++) { ?>
								<div class="item-slide">
									<img src="<?php bloginfo('template_directory') ?>/timthumb.php?src=<?php echo($custom["thumbs"][$i]); ?>&amp;w=298&amp;h=226&amp;zc=1" alt="" />
									<div class="description">
										<p><?php echo($et_description); ?></p>
									</div> <!-- .description -->
									<span class="overlay"></span>
									
								</div> <!-- .item-slide -->
							<?php }; ?>
						</div> <!-- #product-slides -->

						<?php if (count($custom["thumbs"]) > 1) { ?>
							<div id="product-thumbs">
								<?php for ($i = 0; $i <= count($custom["thumbs"])-1; $i++) { ?>
									<a href="#" <?php if($i==0) echo('class="active"'); if ($i==count($custom["thumbs"])-1) echo('class="last"') ?> rel="<?php echo($i+1); ?>">
										<img src="<?php bloginfo('template_directory') ?>/timthumb.php?src=<?php echo($custom["thumbs"][$i]); ?>&amp;w=69&amp;h=69&amp;zc=1" alt="" />
										<span class="overlay"></span>
									</a>
								<?php }; ?>
							</div> <!-- #product-thumbs -->
						<?php }; ?>
					</div> <!-- #product-slider -->
				<?php }; ?>
				<div class="product-info">
					<h1 class="title"><?php the_title(); ?></h1>
					<?php the_bid_form(); ?>
					<div id="bid-history" style="margin-top:1em;">
						<h3>Bid History</h3>
						<?php
						$args = array(
						    'post_type'		=> $market_systems['auctions']->name()."-bids",
							'order_by'		=> 'post_date',
							'post_parent'	=> $post->ID,
						    'post_status'	=> 'revision' );
						$bids = get_posts( $args );
						for ($i = 1; $i <= 10; $i++) {
							$index = sizeof($bids);
							$bid = array_pop( $bids );
							if(empty($bid))
								break;
							$bidder = get_userdata( $bid->post_author );
							$date = date( 'h:i:s a - '.get_option('estore_date_format'),strtotime($bid->post_date));?>
							<li>
								<?php echo $index."." ?>
								<?php echo $bidder->user_login." - " ?>
								<?php// echo $bid->post_content;//bid value ?>
								<?php echo $date ?>
							</li>
							<?php } ?>
						</div>
					<?php if (get_option('estore_postinfo2') <> '' && !$et_page) { ?>
						<p class="post-meta"><?php _e('Ending','eStore'); ?> <?php if( function_exists( 'get_post_end_time' ) ) echo get_post_end_time( '', get_option('estore_date_format') ); ?></p>
						<p class="post-meta"><?php _e('Added','eStore'); ?> <?php if (in_array('date', get_option('estore_postinfo2'))) { ?> <?php _e('on','eStore') ?> <?php the_time(get_option('estore_date_format')) ?><?php }; ?> 
						<!--DOUBLES UP WITH BREADCRUMBS <?php if ( function_exists('pp_get_the_term_list' ) ) { ?> <?php pp_get_the_term_list(); ?><?php }; ?> -->
							</p>
						<?php }; ?>
						<div id="shipping-wrapper">
							<?php do_action('pp_single_shipping_details'); ?>
						</div>
					<div class="clearfix">
						<?php if ($et_price <> '') { ?>
							<span class="price-single"><span><?php echo($et_price); ?></span></span>
							<a href="#" class="addto-cart"><span><?php _e('Add to cart','eStore'); ?></span></a>
						<?php }; ?>
					</div>
				</div> <!-- #product-info -->

				<div class="clear"></div>

				<div class="hr"></div>

				<h2><?php _e('Product Information','eStore'); ?></h2>
				<?php the_content(); ?>
				<div class="clear"></div>
				<?php wp_link_pages(array('before' => '<p><strong>'.__('Pages','eStore').':</strong> ', 'after' => '</p>', 'next_or_number' => 'number')); ?>
				<?php edit_post_link(__('Edit this page','eStore')); ?>

				<div id="nav-below" class="navigation">
					<div class="nav-index">
						<a href="<?php echo $market->get_index_permalink(); ?>">
							<?php printf( __("&larr; Return to %s Index", 'Prospress'), $market->label ); ?>
						</a>
					</div>
				</div>


				<?php $orig_post = $post;
				global $post;
				$tags = wp_get_post_tags($post->ID);
				if ($tags) {
					$tag_ids = array();

					foreach($tags as $individual_tag) $tag_ids[] = $individual_tag->term_id;
					$args=array(
						'tag__in' => $tag_ids,
						'post__not_in' => array($post->ID),
						'showposts'=>4,
						'caller_get_posts'=>1
					);
					$my_query = new wp_query( $args );

					if( $my_query->have_posts() ) { ?>
						<div class="related">
							<h2><?php _e('Related Products','eStore'); ?></h2>
							<ul class="related-items clearfix">
								<?php $i=1; while( $my_query->have_posts() ) {
								$my_query->the_post(); ?>
									<?php $thumb = '';
									$width = 44;
									$height = 44;
									$classtext = '';
									$titletext = get_the_title();

									$thumbnail = get_thumbnail($width,$height,$classtext,$titletext,$titletext);
									$thumb = $thumbnail["thumb"]; ?>

									<li<?php if($i%2==0) echo(' class="second"'); ?>>
										<a href="<?php the_permalink(); ?>" class="clearfix">
											<?php if ($thumb <> '') print_thumbnail($thumb, $thumbnail["use_timthumb"], $titletext, $width, $height, $classtext); ?>
											<span><?php the_title(); ?></span>
										</a>
									</li>
									<?php $i++; ?>
								<?php } ?>
							</ul>
						</div>
					<?php }
				}
				$post = $orig_post;
				wp_reset_query(); ?>						
			</div> <!-- end .post -->
			
			<?php if (get_option('estore_integration_single_bottom') <> '' && get_option('estore_integrate_singlebottom_enable') == 'on') echo(get_option('estore_integration_single_bottom')); ?>
			
			<?php if (get_option('estore_468_enable') == 'on') { ?>
				<?php if(get_option('estore_468_adsense') <> '') echo(get_option('estore_468_adsense'));
				else { ?>
					<a href="<?php echo(get_option('estore_468_url')); ?>"><img src="<?php echo(get_option('estore_468_image')); ?>" alt="468 ad" class="foursixeight" /></a>
				<?php } ?>	
			<?php } ?>
				
		</div> <!-- #left-column -->
	
		<div id="sidebar">
			<?php dynamic_sidebar( $market->name() . '-single-sidebar' ); ?>
		</div> <!-- end #sidebar -->
						
<?php get_footer(); ?>