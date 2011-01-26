<h3 id="deals-title"><span><?php _e('Deals Of The Day','eStore'); ?></span></h3>

<div id="scroller" class="clearfix">
	<a href="#" id="left-arrow"><?php _e('Previous','eStore'); ?></a>

	<div id="items" style="white-space:nowrap;">
		
		<?php $dealsNum = get_option('estore_deals_numposts');
global $market_systems;
		if( isset( $market_systems ) && 'Highest Bids' == get_option('estore_deals_taxonomy') ) {
			global $wpdb;
			//get prospress type posts with the most bids
			$range = get_option('estore_deals_history');
			$results = $wpdb->get_results(
				"SELECT
				 `post_parent` AS \"ID\", COUNT(*) AS \"bids\"
				FROM
				 `wp_posts`
				WHERE
				 `post_type` = \"{$market_systems['auctions']->name()}-bids\"
				AND
				 `post_parent` IN (SELECT `ID` FROM `wp_posts` WHERE `post_type` = \"{$market_systems['auctions']->name()}\" )
				AND
				 `post_modified` > DATE_SUB( NOW(), INTERVAL {$range} HOUR )
				GROUP BY
				 `post_parent`
				ORDER BY
				 `bids` DESC"
			);
			$include = array();
			foreach( $results as $post ) {
				$include[] = $post->ID;
			}
			unset($results);
			if( sizeof($include) > 5 ):
				array_slice ( $include , 0 , $dealsNum );
			else:
	 			$args = array(
				    'numberposts'     => $dealsNum - sizeof($include),
				    'post_type'       => $market_systems['auctions']->name(),
					'order_by'		=> 'post_date',
					'exclude'	=> implode(',',$include),
				    'post_status'     => 'publish' );
				$results = get_posts( $args );
				foreach ( $results as $post ) {
					$include[] = $post->ID;
				}
				unset($results);
			endif;
			$args = array(
				'post_type'=> $market_systems['auctions']->name(),
				'post__in'=> $include,
				'order'=> 'asc',
				'orderby'=>'rand'
				);
		} else {
			$args=array(
				'showposts'=>$dealsNum,
				'cat' => get_cat_ID(get_option('estore_cur_sign')),
			);
		}
		query_posts($args);
		$i = 0;
		if (have_posts()) : while (have_posts()) : the_post(); ?>
			<?php if ( ($i % 4 == 0) || ($i == 0) ) echo ('<div class="block">'); ?>
			
				<div class="item<?php if (($i+1) % 4 == 0) echo(' last'); ?>">
					<div class="item-top"></div>
					
					<div class="item-content">
						<?php $custom = '';
						$custom = get_post_custom($post->ID);
						if( isset( $market_systems ) && get_post_type( $post ) == 'auctions' ){// Prospress
							$winning_bid = $market_systems['auctions']->the_winning_bid_value( $post->ID, false );
							$arr[$i]["price"] = isset( $winning_bid ) ? $winning_bid : '';
						} else {
							$arr[$i]["price"] = isset($custom["price"][0]) ? $custom["price"][0] : '';
							if ($arr[$i]["price"] <> '') $arr[$i]["price"] = get_option('estore_cur_sign') . $arr[$i]["price"];
						}
						$thumb = '';
						$width = 162;
						$height = 112;
						$classtext = '';
						$titletext = get_the_title();

						$thumbnail = get_thumbnail($width,$height,$classtext,$titletext,$titletext);
						$thumb = $thumbnail["thumb"]; ?>
						
						<?php if ($arr[$i]["price"] <> '') { ?>
							<span class="tag"><span><?php echo($arr[$i]["price"]); ?></span></span>
						<?php }; ?>
						
						<?php if ($thumb <> '') { ?>
							<?php print_thumbnail($thumb, $thumbnail["use_timthumb"], $titletext, $width, $height, $classtext); ?>
						<?php }; ?>
						<h4><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
					</div> <!-- .item-content -->
					
					<a href="<?php the_permalink(); ?>" class="more"><span><?php _e('more info','eStore'); ?></span></a>
				</div> <!-- .item -->
						
			<?php if ( ($i+1) % 4 == 0 ) echo ('</div> <!-- end .block -->'); ?>
			
			<?php $i++; ?>
			
		<?php endwhile; ?>
		<?php endif; wp_reset_query(); ?>
		
		<?php if ($dealsNum % 4 <> 0) echo('</div><!-- end .block-->'); ?>
			
	</div> <!-- #items -->
	
	<a href="#" id="right-arrow"><?php _e('Next','eStore'); ?></a>
</div> <!-- #scroller -->

<div class="clear"></div>