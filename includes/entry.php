<?php $thumb = '';
$width = 193;
$height = 130;
$classtext = '';
$titletext = get_the_title();

$thumbnail = get_thumbnail($width,$height,$classtext,$titletext,$titletext,true);
$thumb = $thumbnail["thumb"]; 
$custom = get_post_custom($post->ID);
global $market_systems;
if( isset( $market_systems ) && get_post_type( $post ) == $market_systems['auctions']->name() ){// Prospress
	$winning_bid = $market_systems['auctions']->the_winning_bid_value( $post->ID, false );
	$price = isset( $winning_bid ) ? $winning_bid : '';
} else {
	$price = isset($custom["price"][0]) ? $custom["price"][0] : '';
	if ($price <> '') $price = get_option('estore_cur_sign') . $price;
}
$et_band =  isset($custom["et_band"][0]) ? $custom["et_band"][0] : ''; ?>

<div class="product<?php if ($i % 3 == 0) echo(' last'); ?>">
	<div class="product-content clearfix">
		<a href="<?php the_permalink(); ?>" class="image">
			<span class="rounded" style="background: url('<?php print_thumbnail($thumb, $thumbnail["use_timthumb"], $titletext, $width, $height, $classtext, true, true); ?>') no-repeat;"></span>
			<?php if ($price <> '') { ?>
				<span class="tag"><span><?php echo($price); ?></span></span>
			<?php }; ?>
		</a>
		
		<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
		<p><?php truncate_post(115); ?></p>
		
		<a href="<?php the_permalink(); ?>" class="more"><span><?php _e('more info','eStore'); ?></span></a>
		
		<?php if ($et_band <> '') { ?>
			<span class="band<?php echo(' '.$et_band); ?>"></span>
		<?php }; ?>
	</div> <!-- .product-content -->
</div> <!-- .product -->

<?php if ($i % 3 == 0) echo('<div class="clear"></div>'); ?>