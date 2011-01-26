<?php 
/*
Template Name: Blog Page
*/
?>

<?php the_post(); ?>

<?php 
$et_ptemplate_settings = array();
$et_ptemplate_settings = maybe_unserialize( get_post_meta($post->ID,'et_ptemplate_settings',true) );

$fullwidth = isset( $et_ptemplate_settings['et_fullwidthpage'] ) ? (bool) $et_ptemplate_settings['et_fullwidthpage'] : (bool) $et_ptemplate_settings['et_fullwidthpage'];

$et_ptemplate_blogstyle = isset( $et_ptemplate_settings['et_ptemplate_blogstyle'] ) ? (bool) $et_ptemplate_settings['et_ptemplate_blogstyle'] : (bool) $et_ptemplate_settings['et_ptemplate_blogstyle'];

$et_ptemplate_showthumb = isset( $et_ptemplate_settings['et_ptemplate_showthumb'] ) ? (bool) $et_ptemplate_settings['et_ptemplate_showthumb'] : (bool) $et_ptemplate_settings['et_ptemplate_showthumb'];

$blog_cats = isset( $et_ptemplate_settings['et_ptemplate_blogcats'] ) ? $et_ptemplate_settings['et_ptemplate_blogcats'] : array();
$et_ptemplate_blog_perpage = isset( $et_ptemplate_settings['et_ptemplate_blog_perpage'] ) ? $et_ptemplate_settings['et_ptemplate_blog_perpage'] : 10;
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
					
					<div id="et_pt_blog">
						<?php $cat_query = ''; 
						if ( !empty($blog_cats) ) $cat_query = '&cat=' . implode(",", $blog_cats);
						else echo '<!-- blog category is not selected -->'; ?>
						<?php query_posts("showposts=$et_ptemplate_blog_perpage&paged=$paged" . $cat_query); ?>
						<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
						
							<div class="et_pt_blogentry clearfix">
								<h2 class="et_pt_title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
								
								<p class="et_pt_blogmeta"><?php _e('Posted','eStore'); ?> <?php _e('by','eStore'); ?> <?php the_author_posts_link(); ?> <?php _e('on','eStore'); ?> <?php the_time(get_option('estore_date_format')) ?> <?php _e('in','eStore'); ?> <?php the_category(', ') ?> | <?php comments_popup_link(__('0 comments','eStore'), __('1 comment','eStore'), '% '.__('comments','eStore')); ?></p>
								
								<?php $thumb = '';
								$width = 184;
								$height = 184;
								$classtext = '';
								$titletext = get_the_title();

								$thumbnail = get_thumbnail($width,$height,$classtext,$titletext,$titletext);
								$thumb = $thumbnail["thumb"]; ?>
								
								<?php if ( $thumb <> '' && !$et_ptemplate_showthumb ) { ?>
									<div class="et_pt_thumb alignleft">
										<?php print_thumbnail($thumb, $thumbnail["use_timthumb"], $titletext, $width, $height, $classtext); ?>
										<a href="<?php the_permalink(); ?>"><span class="overlay"></span></a>
									</div> <!-- end .thumb -->
								<?php }; ?>
								
								<?php if (!$et_ptemplate_blogstyle) { ?>
									<p><?php truncate_post(550);?></p>
									<a href="<?php the_permalink(); ?>" class="readmore"><span><?php _e('read more','eStore'); ?></span></a>
								<?php } else { ?>
									<?php the_content(''); ?>
								<?php } ?>
							</div> <!-- end .et_pt_blogentry -->
							
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
					
					</div> <!-- end #et_pt_blog -->
					
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