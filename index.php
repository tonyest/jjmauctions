<?php if (is_archive()) $post_number = get_option('estore_archivenum_posts');
if (is_search()) $post_number = get_option('estore_searchnum_posts');
if (is_tag()) $post_number = get_option('estore_tagnum_posts');
if (is_category()) $post_number = get_option('estore_catnum_posts'); ?>
<?php get_header(); ?>
	
<?php include(TEMPLATEPATH . '/includes/breadcrumbs.php'); ?>

<div id="main-area">
	<div id="main-content" class="clearfix">
		<?php error_log('index')?>
<?php if(is_single())
		error_log('single');
	?>
		<?php if (is_category()) 
			query_posts($query_string . "&showposts=$post_number&paged=$paged&cat=$cat");
		else 
			query_posts($query_string . "&showposts=$post_number&paged=$paged"); ?>

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