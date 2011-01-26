<?php
/*
Template Name: Auctions Index
*/
/**
 * The main index for auctions listings.
 */
wp_enqueue_style( 'prospress',  PP_CORE_URL . '/prospress.css' );
?>

<?php get_header(); ?>
	
<?php include(TEMPLATEPATH . '/includes/breadcrumbs.php'); ?>

<div id="main-area">
	<div id="main-content" class="clearfix">

		<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>

		<div id="left-column">
			<div class="post clearfix">
			<h1 class="title"><?php the_title(); ?></h1>
			<div class="prospres-content entry-content"><?php the_content(); ?></div>
			</div>
		<div class="clear"></div>
		<?php endwhile; ?>


		<?php query_posts( array( 'post_type' => $market->name(), 'post_status' => 'publish' ) ); ?>

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
	
		<div id="sidebar">
			<?php dynamic_sidebar( $market->name() . '-index-sidebar' ); ?>
		</div> <!-- end #sidebar -->
						
<?php get_footer(); ?>