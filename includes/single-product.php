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
	<?php if (get_option('estore_postinfo2') <> '' && !$et_page) { ?>
		<p class="post-meta"><?php _e('Added','eStore'); ?> <?php if (in_array('date', get_option('estore_postinfo2'))) { ?> <?php _e('on','eStore') ?> <?php the_time(get_option('estore_date_format')) ?><?php }; ?> <?php if (in_array('categories', get_option('estore_postinfo2'))) { ?> <?php _e('in','eStore'); ?> <?php the_category(', ') ?><?php }; ?></p>
	<?php }; ?>
	
	<div class="clearfix">
		<?php if ($et_price <> '') { ?>
			<span class="price-single"><span><?php echo($et_price); ?></span></span>
			<a href="#" class="addto-cart"><span><?php _e('Add to cart','eStore'); ?></span></a>
		<?php }; ?>
	</div>
	
	<div class="description">
		<p><?php echo($et_description); ?></p>
	</div> <!-- .description -->

</div> <!-- #product-info -->

<div class="clear"></div>

<div class="hr"></div>

<h2><?php _e('Product Information','eStore'); ?></h2>
<?php the_content(); ?>
<div class="clear"></div>
<?php wp_link_pages(array('before' => '<p><strong>'.__('Pages','eStore').':</strong> ', 'after' => '</p>', 'next_or_number' => 'number')); ?>
<?php edit_post_link(__('Edit this page','eStore')); ?>


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