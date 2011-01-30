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
	
<?php include(STYLESHEETPATH . '/includes/breadcrumbs.php'); ?>

<div id="main-area">
	<div id="main-content" class="clearfix">

		<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
			
		<?php global $query_string;?>
		<?php parse_str($query_string,$args);?>
		 <?php $page = get_post($args['page_id']);?>
		<div id="left-column">
			<div class="post clearfix">
				<h1 class="title"><?php echo $page->post_title ?></h1>
					<div class="prospres-content entry-content"><?php echo $page->post_content ?></div>
			</div>
		<div class="clear"></div>
		<?php endwhile; ?>
		<?php
		unset($args['page_id']);
		$merge = array_merge(array( 'post_type' => $market->name(), 'post_status' => 'publish' ), $args);
		?>
		<?php query_posts( $merge )?>
			<?php $i = 1; ?>
			
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
			<?php endif;?>
		</div> <!-- #left-column -->
	
		<div id="sidebar">
			<?php dynamic_sidebar( $market->name() . '-index-sidebar' ); ?>
		</div> <!-- end #sidebar -->
						
<?php get_footer(); ?>