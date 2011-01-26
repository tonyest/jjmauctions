<?php the_post(); ?>

<?php get_header(); ?>

<?php include(TEMPLATEPATH . '/includes/breadcrumbs.php'); ?>
	
<div id="main-area">
	<div id="main-content" class="clearfix">
		<div id="left-column">
			<?php if (get_option('estore_integration_single_top') <> '' && get_option('estore_integrate_singletop_enable') == 'on') echo(get_option('estore_integration_single_top')); ?>
	
			<div class="post clearfix">				
				
				<?php include(TEMPLATEPATH . '/includes/single-product.php'); ?>
						
			</div> <!-- end .post -->
			
			<?php if (get_option('estore_integration_single_bottom') <> '' && get_option('estore_integrate_singlebottom_enable') == 'on') echo(get_option('estore_integration_single_bottom')); ?>
			
			<?php if (get_option('estore_468_enable') == 'on') { ?>
				<?php if(get_option('estore_468_adsense') <> '') echo(get_option('estore_468_adsense'));
				else { ?>
					<a href="<?php echo(get_option('estore_468_url')); ?>"><img src="<?php echo(get_option('estore_468_image')); ?>" alt="468 ad" class="foursixeight" /></a>
				<?php } ?>	
			<?php } ?>
				
		</div> <!-- #left-column -->
	
		<?php get_sidebar(); ?>
<?php get_footer(); ?>