<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
<head profile="http://gmpg.org/xfn/11">
<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
<title><?php elegant_titles(); ?></title>
<?php elegant_description(); ?>
<?php elegant_keywords(); ?>
<?php elegant_canonical(); ?>

<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />
<link rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?> RSS Feed" href="<?php bloginfo('rss2_url'); ?>" />
<link rel="alternate" type="application/atom+xml" title="<?php bloginfo('name'); ?> Atom Feed" href="<?php bloginfo('atom_url'); ?>" />
<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />

<!--[if lt IE 7]>
	<link rel="stylesheet" type="text/css" href="<?php bloginfo('template_directory'); ?>/css/ie6style.css" />
	<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/js/DD_belatedPNG_0.0.8a-min.js"></script>
	<script type="text/javascript">DD_belatedPNG.fix('img#logo');</script>
<![endif]-->
<!--[if IE 7]>
	<link rel="stylesheet" type="text/css" href="<?php bloginfo('template_directory'); ?>/css/ie7style.css" />
<![endif]-->
<!--[if IE 8]>
	<link rel="stylesheet" type="text/css" href="<?php bloginfo('template_directory'); ?>/css/ie8style.css" />
<![endif]-->

<script type="text/javascript">
	document.documentElement.className = 'js';
</script>

<?php if ( is_singular() ) wp_enqueue_script( 'comment-reply' ); ?>
<?php wp_head(); ?>

</head>
<body<?php if (is_home()) echo(' id="home"'); if ( get_option('estore_cufon') == 'false' ) echo(' class="cufon-disabled"'); else echo(' class="cufon-enabled"'); ?>>
	<div id="header">
		<div class="container clearfix">
			<a href="<?php bloginfo('url'); ?>"><?php $logo = (get_option('estore_logo') <> '') ? get_option('estore_logo') : get_bloginfo('template_directory').'/images/logo.png'; ?>
				<img src="<?php echo $logo; ?>" alt="Logo" id="logo"/></a>
			
			<?php $menuClass = 'nav superfish clearfix';
			$menuID = 'top-menu';
			$primaryNav = '';
			if (function_exists('wp_nav_menu')) {
				$primaryNav = wp_nav_menu( array( 'theme_location' => 'primary-menu', 'container' => '', 'fallback_cb' => '', 'menu_class' => $menuClass, 'menu_id' => $menuID, 'echo' => false ) ); 
			};
			if ($primaryNav == '') { ?>
				<ul id="<?php echo $menuID; ?>" class="<?php echo $menuClass; ?>">
					<?php if (get_option('estore_swap_navbar') == 'false') { ?>
						<?php if (get_option('estore_home_link') == 'on') { ?>
							<li <?php if (is_home()) echo('class="current_page_item"') ?>><a href="<?php bloginfo('url'); ?>"><?php _e('Home','eStore') ?></a></li>
						<?php }; ?>
						
						<?php show_page_menu($menuClass,false,false); ?>
					<?php } else { ?>
						<?php show_categories_menu($menuClass,false); ?>
					<?php } ?>
				</ul> <!-- ul#nav -->
			<?php }
			else echo($primaryNav); ?>
			
			<div id="search-bar">
				<form method="get" id="searchform1" action="<?php bloginfo('url'); ?>">
					<input type="text" value="<?php _e('search this site...','eStore'); ?>" name="s" id="searchinput" />

					<input type="image" src="<?php bloginfo('template_directory'); ?>/images/<?php echo($colorSchemePath); ?>search-icon.png" id="searchsubmit" />
				</form>
			</div> <!-- #search-bar -->
						
			<div id="menu">
				<?php $menuClass = 'nav superfish clearfix';
				$menuID = 'secondary-menu';
				$secondaryNav = '';
				if (function_exists('wp_nav_menu')) {
					$secondaryNav = wp_nav_menu( array( 'theme_location' => 'secondary-menu', 'container' => '', 'fallback_cb' => '', 'menu_class' => $menuClass, 'menu_id' => $menuID, 'echo' => false, 'walker' => new description_walker() ) ); 
				};
				if ($secondaryNav == '') { ?>
					<ul id="<?php echo $menuID; ?>" class="<?php echo $menuClass; ?>">
						<?php if (get_option('estore_swap_navbar') == 'false') { ?>
							<?php show_categories_menu($menuClass,false); ?>
						<?php } else { ?>
							<?php if (get_option('estore_home_link') == 'on') { ?>
								<li <?php if (is_home()) echo('class="current_page_item"') ?>><a href="<?php bloginfo('url'); ?>"><?php _e('Home','eStore') ?></a></li>
							<?php }; ?>
							
							<?php show_page_menu($menuClass,false,false); ?>
						<?php } ?>
					</ul> <!-- end ul#nav -->
				<?php }
				else echo($secondaryNav); ?>
			</div> <!-- #menu -->
			
		</div> <!-- .container -->
	</div> <!-- #header -->
		
	<?php
	 if (get_option('estore_featured') == 'on' && (is_home() || is_front_page())) include(TEMPLATEPATH . '/includes/featured.php'); 
	?>
		
	<div id="content" <?php global $fullwidth; if ( is_page_template('page-full.php') || $fullwidth ) echo 'class="no_sidebar"'?>>
		<div class="container clearfix">
			<?php global $market_systems; ?>
				<?php if (get_option('estore_deals_in_index') == 'on' && $market_systems['auctions']->is_index() ) {?>
					<div class="container clearfix">
					<?php include(TEMPLATEPATH . '/includes/scroller.php'); 
				} ?>