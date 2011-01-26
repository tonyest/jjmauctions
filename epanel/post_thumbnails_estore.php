<?php 

/* sets predefined Post Thumbnail dimensions */
if ( function_exists( 'add_theme_support' ) ) {
	add_theme_support( 'post-thumbnails' );
	
	//blog page template
	add_image_size( 'ptentry-thumb', 184, 184, true );
	//gallery page template
	add_image_size( 'ptgallery-thumb', 207, 136, true );
		
	//featured image size
	add_image_size( 'featured-thumb', 1400, 501, true );
	add_image_size( 'featured-small', 109, 109, true );
	
	add_image_size( 'scroller', 162, 112, true );
	add_image_size( 'entry-thumb', 193, 130, true );
	add_image_size( 'related', 44, 44, true );
	
};
/* --------------------------------------------- */

?>