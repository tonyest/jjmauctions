<?php get_header(); ?>
	
<?php if (get_option('estore_scroller') == 'on') include(STYLESHEETPATH . '/includes/scroller.php'); ?>

<?php 
	$args=array(
		'showposts'=>get_option('estore_homepage_posts'),
		'paged'=>$paged,
		'category__not_in' => get_option('estore_exlcats_recent'),
	);
	if (get_option('estore_duplicate') == 'false') $args['post__not_in'] = $ids;
	query_posts($args); ?>

<div id="main-area">
	<div id="main-content" class="clearfix">
		<div id="left-column">
			<?php $i = 1; ?>
			<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
				<?php include(TEMPLATEPATH . '/includes/entry.php'); ?>
				<?php $i++; ?>
			<?php endwhile; ?>
				<?php if (($i-1) % 3 <> 0) echo('<div class="clear"></div>'); ?>
				<?php if(function_exists('wp_pagenavi')) { wp_pagenavi(); }
				else { ?>
					 <?php include(TEMPLATEPATH . '/includes/navigation.php'); ?>
				<?php } ?>	
			<?php else : ?>
				<?php include(TEMPLATEPATH . '/includes/no-results.php'); ?>
			<?php endif; wp_reset_query(); ?>

		</div> <!-- #left-column -->

		<?php get_sidebar(); ?>

<?php get_footer(); ?>			