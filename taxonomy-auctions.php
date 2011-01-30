<?php
/*
Template Name: Auctions Taxonomy Index
*/
/**
 * The main index for auctions listings.
 */
wp_enqueue_style( 'prospress',  PP_CORE_URL . '/prospress.css' );
?>

<?php get_header(); ?>

<?php include(STYLESHEETPATH . '/includes/breadcrumbs.php'); ?>

<div id="main-area">
	<div id="main-content" class="clearfix">

		<div id="left-column">
			<div class="post clearfix">
			<?php if( isset( $tax_title ) ) echo '<h1 class="title">' . $tax_title . '</h1>'; ?>
			<?php 
			if ( !empty( $term_description ) )
				echo '<div class="prospress-archive-meta"><em>' . $term_description . '</em></div>';
			?>
			</div>
			<div class="clear"></div>

			<?php $i = 1; ?>
			<?php global $query_string;?>

			<?php query_posts($query_string)?>
			<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
				<?php include(STYLESHEETPATH . '/includes/entry.php'); ?>
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
	
		<div id="sidebar">
			<?php dynamic_sidebar( $market->name() . '-index-sidebar' ); ?>
		</div> <!-- end #sidebar -->
						
<?php get_footer(); ?>