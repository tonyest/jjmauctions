
<div id="breadcrumbs">
	<?php if(function_exists('bcn_display')) { bcn_display(); } 
		  else { ?>
				<a href="<?php bloginfo('url'); ?>"><?php _e('Home','eStore') ?></a> <span class="sep"></span>
				
				<?php if( is_tag() ) { ?>
					<?php _e('Posts Tagged &quot;','eStore') ?><?php single_tag_title(); echo('&quot;'); ?>
				<?php } elseif (is_day()) { ?>
					<?php _e('Posts made in','eStore') ?> <?php the_time('F jS, Y'); ?>
				<?php } elseif (is_month()) { ?>
					<?php _e('Posts made in','eStore') ?> <?php the_time('F, Y'); ?>
				<?php } elseif (is_year()) { ?>
					<?php _e('Posts made in','eStore') ?> <?php the_time('Y'); ?>
				<?php } elseif (is_search()) { ?>
					<?php _e('Search results for','eStore') ?> <?php the_search_query() ?>
				<?php } elseif (is_single()) { ?>
					<?php
						global $post;
						add_filter('taxonomy_template',create_function( '$template' , '$template = "%s: %l <span class=\"sep\"></span> "; return $template;' ),1,1);
						$taxonomies = get_the_taxonomies( $post );
						if( !empty( $taxonomies ) ) {
							foreach ( $taxonomies as $taxname ) {
								echo $taxname;
							}
							wp_title('');
						}
					?>
				<?php } elseif (is_category()) { ?>
					<?php single_cat_title(); ?>
				<?php } elseif (is_author()) { ?>
					<?php global $wp_query;
						  $curauth = $wp_query->get_queried_object(); ?>
					<?php _e('Posts by ','eStore'); echo ' ',$curauth->nickname; ?>
				<?php } elseif (is_page()) { ?>
					<?php wp_title(''); ?>
				<?php } elseif (is_archive() && !is_tax()) { ?>
					<?php wp_title('');?>
				<?php }; ?>
	<?php }; ?>

</div> <!-- end #breadcrumbs -->
