<?php 
/*
Template Name: Sitemap Page
*/
?>

<?php the_post(); ?>

<?php 
$et_ptemplate_settings = array();
$et_ptemplate_settings = maybe_unserialize( get_post_meta($post->ID,'et_ptemplate_settings',true) );

$fullwidth = isset( $et_ptemplate_settings['et_fullwidthpage'] ) ? (bool) $et_ptemplate_settings['et_fullwidthpage'] : (bool) $et_ptemplate_settings['et_fullwidthpage'];
?>

<?php get_header(); ?>

<?php include(TEMPLATEPATH . '/includes/breadcrumbs.php'); ?>
	
<div id="main-area">
	<div id="main-content" class="clearfix">
		<div id="left-column">
				
			<div class="post clearfix">				
				<?php $custom = get_post_custom($post->ID);
				$et_page_template = isset($custom["et_page_template"][0]) ? $custom["et_page_template"][0] : '';
				$et_page = true;
				?>
					<h1 class="title"><?php the_title(); ?></h1>
					<?php the_content(); ?>
					<div class="clear"></div>
					<?php wp_link_pages(array('before' => '<p><strong>'.__('Pages','eStore').':</strong> ', 'after' => '</p>', 'next_or_number' => 'number')); ?>
					
					<div id="sitemap">
						<div class="sitemap-col">
							<h2><?php _e('Pages','eStore'); ?></h2>
							<ul id="sitemap-pages"><?php wp_list_pages('title_li='); ?></ul>
						</div> <!-- end .sitemap-col -->
						
						<div class="sitemap-col">
							<h2><?php _e('Categories','eStore'); ?></h2>
							<ul id="sitemap-categories"><?php wp_list_categories('title_li='); ?></ul>
						</div> <!-- end .sitemap-col -->
						
						<div class="sitemap-col">
							<h2><?php _e('Tags','eStore'); ?></h2>
							<ul id="sitemap-tags">
								<?php $tags = get_tags();
								if ($tags) {
									foreach ($tags as $tag) {
										echo '<li><a href="' . get_tag_link( $tag->term_id ) . '">' . $tag->name . '</a></li> ';
									}
								} ?>
							</ul>
						</div> <!-- end .sitemap-col -->
												
						<div class="sitemap-col<?php echo ' last'; ?>">
							<h2><?php _e('Authors','eStore'); ?></h2>
							<ul id="sitemap-authors" ><?php wp_list_authors('show_fullname=1&optioncount=1&exclude_admin=0'); ?></ul>
						</div> <!-- end .sitemap-col -->
					</div> <!-- end #sitemap -->
					
					<div class="clear"></div>
					
					<?php edit_post_link(__('Edit this page','eStore')); ?>
				
			</div> <!-- end .post -->
			
			<?php if (get_option('estore_integration_single_bottom') <> '' && get_option('estore_integrate_singlebottom_enable') == 'on') echo(get_option('estore_integration_single_bottom')); ?>
			
			<?php if (get_option('estore_468_enable') == 'on') { ?>
				<?php if(get_option('estore_468_adsense') <> '') echo(get_option('estore_468_adsense'));
				else { ?>
					<a href="<?php echo(get_option('estore_468_url')); ?>"><img src="<?php echo(get_option('estore_468_image')); ?>" alt="468 ad" class="foursixeight" /></a>
				<?php } ?>	
			<?php } ?>
				
		</div> <!-- #left-column -->
	
		<?php if (!$fullwidth) get_sidebar(); ?>
		
<?php get_footer(); ?>