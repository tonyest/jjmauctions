<?php 
require_once(__DIR__ . '/epanel/options_estore.php');

/**
 * Functions for Prospress
 **/ 

// Add the post thumbnail meta boxes to Edit Auction admin page
function ppes_admin_init(){
	add_meta_box("et_post_meta", "ET Settings", "et_post_meta", "auctions", "normal", "high");
}
add_action( "admin_init", "ppes_admin_init" );

// Register Custom Side bars for Auction Index and Single templates
global $market_systems;

if( !empty( $market_systems ) && function_exists('register_sidebar') ) {
	register_sidebar( array(
		'name' => $market_systems[ 'auctions' ]->label . ' ' . __( 'Index Sidebar', 'prospress' ),
		'id' => $market_systems[ 'auctions' ]->name() . '-index-sidebar',
		'description' => sprintf( __( "The sidebar on the %s index.", 'prospress' ), $market_systems[ 'auctions' ]->label ),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div> <!-- .widget-content --></div> <!-- end .widget -->',
		'before_title' => '<h4 class="widgettitle">',
		'after_title' => '</h4><div class="widget-content">',
	) );
	register_sidebar( array(
		'name' => sprintf( __( 'Single %s Sidebar', 'prospress' ), $market_systems[ 'auctions' ]->labels[ 'singular_name' ] ),
		'id' => $market_systems[ 'auctions' ]->name() . '-single-sidebar',
		'description' => sprintf( __( "The sidebar on a single %s.", 'prospress' ), $market_systems[ 'auctions' ]->labels[ 'singular_name' ] ),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div> <!-- .widget-content --></div> <!-- end .widget -->',
		'before_title' => '<h4 class="widgettitle">',
		'after_title' => '</h4><div class="widget-content">',
	) );
}

require_once(TEMPLATEPATH . '/includes/functions/additional_functions.php');
