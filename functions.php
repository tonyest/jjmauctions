<?php 
// call template first so we can 'customize' the options
require_once(TEMPLATEPATH . '/epanel/options_estore.php');
// require_once(STYLESHEETPATH . '/epanel/options_estore.php');
global $market_systems;
$site_taxs[] = "Highest Bids";
/*
 *
 *	filter through options and modify for our evil plans
 *	this is a little hacky, but thats what you get when you use shiny looking options builders
 * 
 */
foreach ( $options as $index => &$option ) {
	
	if ( $option['id'] == "estore_deals_category") {
		$estore_deals_category_index = $index;
		$option = array(
			"name" => "'Deals Of The Day' Taxonomy",
			"id" => $shortname."_deals_taxonomy",
			"type" => "select",
			"options" => $site_taxs,
			"std" => "Highest Bids",
			"desc" => "description"
		);
	}
	if ( $option['id'] == "estore_scroller" ) {
		$estore_scroller_index = $index;
	}
}
array_splice( $options, (int)$estore_deals_category_index, 0, array(
	array(
		"name" => "'Deals Of The Day' Highest-Bid Sample Range (hrs)",
		"id" => $shortname."_deals_history",
		"type"=> "text",
		"std" => "24",
		"desc"=>"desc.")
	)
);
unset($estore_deals_category_index);
array_splice( $options, (int)$estore_scroller_index, 0, array(
	array( 
		"name" => "Display 'Deals Of The Day' on Prospress Index Page",
		"id" => $shortname."_deals_in_index",
		"type" => "checkbox",
		"std" => "on",
		"desc" => "desc.")
	)
);
unset($estore_scroller_index);

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
