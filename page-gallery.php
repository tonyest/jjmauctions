<?php 
/*
Template Name: Gallery Page
*/
?>

<?php the_post(); ?>

<?php 
$et_ptemplate_settings = array();
$et_ptemplate_settings = maybe_unserialize( get_post_meta($post->ID,'et_ptemplate_settings',true) );

$fullwidth = isset( $et_ptemplate_settings['et_fullwidthpage'] ) ? (bool) $et_ptemplate_settings['et_fullwidthpage'] : (bool) $et_ptemplate_settings['et_fullwidthpage'];

$gallery_cats = isset( $et_ptemplate_settings['et_ptemplate_gallerycats'] ) ? $et_ptemplate_settings['et_ptemplate_gallerycats'] : array();
$et_ptemplate_gallery_perpage = isset( $et_ptemplate_settings['et_ptemplate_gallery_perpage'] ) ? $et_ptemplate_settings['et_ptemplate_gallery_perpage'] : 12;
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
					
					<div id="et_pt_gallery" class="clearfix">
						<?php $gallery_query = ''; 
						if ( !empty($gallery_cats) ) $gallery_query = '&cat=' . implode(",", $gallery_cats);
						else echo '<!-- gallery category is not selected -->'; ?>
						<?php query_posts("showposts=$et_ptemplate_gallery_perpage&paged=$paged" . $gallery_query); ?>
						<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
							
							<?php $width = 207;
							$height = 136;
							$titletext = get_the_title();

							$thumbnail = get_thumbnail($width,$height,'portfolio',$titletext,$titletext,true,'Portfolio');
							$thumb = $thumbnail["thumb"]; ?>
							
							<div class="et_pt_gallery_entry">
								<div class="et_pt_item_image">
									<?php print_thumbnail($thumb, $thumbnail["use_timthumb"], $titletext, $width, $height, 'portfolio'); ?>
									<span class="overlay"></span>
									
									<a class="fancybox zoom-icon" title="<?php the_title(); ?>" rel="gallery" href="<?php echo($thumbnail['fullpath']); ?>"><?php _e('Zoom in','eStore'); ?></a>
									<a class="more-icon" href="<?php the_permalink(); ?>"><?php _e('Read more','eStore'); ?></a>
								</div> <!-- end .et_pt_item_image -->
							</div> <!-- end .et_pt_gallery_entry -->
							
						<?php endwhile; ?>
							<div class="page-nav clearfix">
								<?php if(function_exists('wp_pagenavi')) { wp_pagenavi(); }
								else { ?>
									 <?php include(TEMPLATEPATH . '/includes/navigation.php'); ?>
								<?php } ?>
							</div> <!-- end .entry -->
						<?php else : ?>
							<?php include(TEMPLATEPATH . '/includes/no-results.php'); ?>
						<?php endif; wp_reset_query(); ?>
					
					</div> <!-- end #et_pt_gallery -->
					
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