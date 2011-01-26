<?php
if ( function_exists('register_sidebar') )
register_sidebar(array(
	'name' => 'Sidebar',
	'before_widget' => '<div id="%1$s" class="widget %2$s">',
	'after_widget' => '</div> <!-- .widget-content --></div> <!-- end .widget -->',
	'before_title' => '<h4 class="widgettitle">',
	'after_title' => '</h4><div class="widget-content">',
));
?>