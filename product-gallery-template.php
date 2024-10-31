<?php
$tax='categories';

// Create Random Category Gallery View run from WIDGET
function rpg_tem_rnd_category( $max, $cat_image_size ) {
	$counter = 0;

	//for a given post type, return all
	global $tax;

	// Query Arguments
	$args = array (
		'taxonomy' => $tax,
		'orderby' => 'name',
		'order' => 'ASC',
		'hide_empty' => false, //shows empty categories
	);
	$categories = get_categories( $args );
	shuffle ($categories);
	?>
	
	<?php
	foreach ($categories as $category) 
	{
		if ($counter < $max )
		{
			if (wpds_tax_pic_url($category->term_id)<>'')
			{
				$counter++;
				echo '<div class="col-lg-' . $cat_image_size . ' col-sm-' . $cat_image_size . ' col-md-'. $cat_image_size . '"  style="padding:2px;">';
				echo '<a href="'. get_permalink( get_option('product_page' . $category->term_id) ) . '" class="thumbnail">';
		echo '<img src="'. wpds_tax_pic_url($category->term_id).'" /></a>';
		echo '</div>';
		//		echo $category->term_id . ' === ' . $category->count . ' ' . $category->name;
			}
		}
	}
	
	// Reset Post Data
	wp_reset_postdata();
}
	

// Create Random Product View run from WIDGET
function rpg_tem_rnd_product( $max, $new_prod_only, $open_prod_url, $image_size ) {
	$counter = 0;

	//for a given post type, return all
	global $tax;

	$args = array(
		'post_type' => 'gallery',
		'posts_per_page' => '-1',
		"$tax" => $slug,
		'orderby' => 'rand',
	);  
	// The Query
	$the_query = new WP_Query( $args );

	// Check if the Query returns any posts
	if ( $the_query->have_posts() ) {

		// Start the product 
        $dID='';
        if ($open_prod_url!=1) { $dID='id="prod_thumb"'; }
// '<div class="col-lg-4 col-sm-4 col-md-4" style="padding:2px;">';
		?>
		<div <?php echo $dID; ?> style='float:left;' ">
				<?php
				// The Loop
				while ( $the_query->have_posts() ) : $the_query->the_post(); 
				$show_image=false;
				if ($counter < $max )
				{
					if (get_post_meta( get_the_id(), 'newprod', true)==1)
					{
						$show_image=true;
					}
					if ($new_prod_only!=1) { $show_image=true; }

					if ($show_image==1)
					{
					$counter++;
					
					$large_image_url = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'medium' );
					$image_url=$large_image_url[0];
					if ($open_prod_url==1) { $image_url=get_post_meta(get_the_id(),'galleryurl', true); }
					echo '<div class="col-lg-' . $image_size . ' col-sm-' . $image_size . ' col-md-'. $image_size . '">';
					echo '<a href="' . $image_url . '" title="' . the_title_attribute( 'echo=0' ) . '" class="thumbnail" style="border:none;" >';
					echo '<img src="' . $large_image_url[0] . '" />';
					echo '</a>';
					echo '</div>';
					}
				}
				endwhile; ?>
		</div>
	<?php 
	}
	// Reset Post Data
	wp_reset_postdata();
}

	
// Create Product Gallery
function rpg_tem_lightbox_product_gallery( $per_page, $slug, $backcolor ) {
	$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

	//for a given post type, return all
	global $tax;

	// Query Arguments
	$args = array(
		'post_type' => 'gallery',
		'posts_per_page' => $per_page,
		"$tax" => $slug,
		'orderby' => '',
		'paged' => $paged
	);  
	// The Query
	$the_query = new WP_Query( $args );

	$total = $the_query->max_num_pages;

	// Check if the Query returns any posts
	if ( $the_query->have_posts() ) {

		// Start the product ?>
		<div id="thumbnails">
				<?php
				// The Loop
				while ( $the_query->have_posts() ) : $the_query->the_post(); 
					$large_image_url = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'large' );
					echo '<div class="col-lg-4 col-sm-4 col-md-4" style="background-color:' . $backcolor . ';" >';
					echo '<a href="' . $large_image_url[0] . '" title="' . the_title_attribute( 'echo=0' ) . '" class="thumbnail" >';
//					echo get_the_post_thumbnail( $post->ID, array( 100, 100) );
					echo get_the_post_thumbnail( $post->ID, 'medium' );
//					echo '<img src="' . $large_image_url[0] . '" />';
					echo '</a>';
					echo '</div>';
				endwhile; ?>
		</div>
		<div class="paginate">
			<div class="left"><?php previous_posts_link( "<img src=" . plugins_url('images/previous.png', __FILE__ ) . " width='32' />" ); ?>&nbsp;</div>
			<div class="center">
			<?php if ( $total>1 ) { echo $paged . '/' . $total; } ?>
			</div>
			<div class="right">
			<?php next_posts_link( "<img src=" . plugins_url('images/next.png', __FILE__ ) . " width='32' />", $the_query->max_num_pages );?></div>
		</div>

	<?php 
	}
	// Reset Post Data
	wp_reset_postdata();
}




// Create Product Gallery with Short Description
function rpg_tem_lightbox_product_gallery_short_desc( $per_page, $slug, $backcolor ) {
	$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

	//for a given post type, return all
	global $tax;

	// Query Arguments
	$args = array(
		'post_type' => 'gallery',
		'posts_per_page' => $per_page,
		"$tax" => $slug,
		'orderby' => '',
		'paged' => $paged
	);  
	// The Query
	$the_query = new WP_Query( $args );

	$total = $the_query->max_num_pages;

	// Check if the Query returns any posts
	if ( $the_query->have_posts() ) {

		// Start the product ?>
		<div id="thumbnails">
				<?php
				// The Loop
				while ( $the_query->have_posts() ) : $the_query->the_post(); 
					$large_image_url = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'large' );
					echo '<div class="col-lg-4 col-sm-4 col-md-4" style="background-color:' . $backcolor . '; border:1px solid #bebebe; padding-top:10px;" >';
					echo '<a href="' . $large_image_url[0] . '" title="' . the_title_attribute( 'echo=0' ) . '" class="thumbnail" style="border:none;">';
//					echo get_the_post_thumbnail( $post->ID, array( 100, 100) );
					echo get_the_post_thumbnail( $post->ID, 'medium' );
//					echo '<img src="' . $large_image_url[0] . '" />';
					echo '</a>';
						echo '<div class="caption">';
//						$attachment = get_post( get_post_thumbnail_id( $post->ID ) );
// caption : $attachment->post_excerpt , description : post_content , title : post_title, guid : guid
						echo '<h5 style="text-align:center;">' . the_title_attribute( 'echo=0' ) . '</h5>';
						echo '<p>' . get_post_meta( get_the_id(), 'prodshortdesc', true) . '</p>';
						if (get_post_meta( get_the_id(), 'prodrate', true)>0)
						{
						echo '<h5 style="text-align:center;">Rate: ' . get_post_meta( get_the_id(), 'prodrate', true) . '</h5>';
						}
						echo '</div>';
					echo '</div>';
				endwhile; ?>
		</div>
		<div class="paginate">
			<div class="left"><?php previous_posts_link( "<img src=" . plugins_url('images/previous.png', __FILE__ ) . " width='32' />" ); ?>&nbsp;</div>
			<div class="center">
			<?php if ( $total>1 ) { echo $paged . '/' . $total; } ?>
			</div>
			<div class="right">
			<?php next_posts_link( "<img src=" . plugins_url('images/next.png', __FILE__ ) . " width='32' />", $the_query->max_num_pages );?></div>
		</div>

	<?php 
	}
	// Reset Post Data
	wp_reset_postdata();
}







// Create Category Page
function tem_category_gallery( $width, $backcolor ) {

	//for a given post type, return all
	global $tax;

	// Query Arguments
	$args = array (
		'taxonomy' => $tax,
		'orderby' => 'name',
		'order' => 'ASC',
		'hide_empty' => false, //shows empty categories
	);
	$categories = get_categories( $args );
//		shuffle ($categories);
	echo '<div class="cat_text" style="background-color:<?php echo $backcolor; ?>;" >';
	foreach ($categories as $category) 
	{
		echo '<a href="'. get_permalink( get_option('product_page' . $category->term_id) ) . '">';
echo '<img src="'. wpds_tax_pic_url($category->term_id).'" style="width:' .$width . 'px;" /></a>';
//		echo $category->term_id . ' === ' . $category->count . ' ' . $category->name;
	}
	echo '</div>';
	// Reset Post Data
	wp_reset_postdata();
}
?>