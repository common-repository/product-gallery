<?php  
/* 
Plugin Name: Product Gallery
Plugin URI: http://www.hpinfosys.com/ 
Description: Responsive ( BOOTSTRAP ) Products Gallery view on Footer, Page & Sidebar.
Version: 6.2
Author: Hitesh Patel
Author URI: http://hpinfosys.com
*/ 

wp_enqueue_script('jquery');
wp_enqueue_style( 'product-style',plugins_url('css/products.css', __FILE__));
wp_enqueue_style( 'hp-lightbox-style',plugins_url('css/styles.css', __FILE__));

wp_enqueue_style( 'hp-lightboxj-style',plugins_url('css/jquery.lightbox-0.5.css', __FILE__));
wp_register_script('hp-lightbox-script',plugins_url('js/jquery.lightbox-0.5.min.js', __FILE__),array(),false,false);
wp_enqueue_script('hp-lightbox-script');

// images path using in JS file
$wnm_custom = array( 'next_btn' => plugins_url('images/lightbox-btn-next.gif', __FILE__), 'prev_btn' =>  plugins_url('images/lightbox-btn-prev.gif', __FILE__), 'close_btn' => plugins_url('images/lightbox-btn-close.gif', __FILE__),  'loading' => plugins_url('images/lightbox-ico-loading.gif', __FILE__), 'blank' => plugins_url('images/lightbox-blank.gif', __FILE__));
wp_localize_script( 'hp-lightbox-script', 'image_path', $wnm_custom );

include( plugin_dir_path( __FILE__ ) . 'product-gallery-template.php');
include( plugin_dir_path( __FILE__ ) . 'product-gallery-post.php');
include( plugin_dir_path( __FILE__ ) . 'product-gallery-widget.php');
include( plugin_dir_path( __FILE__ ) . 'product-gallery-shortcode.php');
include( plugin_dir_path( __FILE__ ) . 'product-gallery-categories.php');

//	add_image_size( 'product-gallery-large', 1920, 1080, true );


function rpg_lightbox_initialize() { ?>
	<script type="text/javascript">
	jQuery(function() {
		jQuery('#thumbnails a').lightBox();
		jQuery('#prod_thumb a').lightBox();
	});
	</script>
<?php }
add_action( 'wp_footer', 'rpg_lightbox_initialize' );    


// Create Custom User Role
function rpg_add_product_management_role() {
	add_role('product_manager', 'Product Manager', 
	array(
			'edit_gallery' => true,
			'delete_gallery' => true,
			'read_gallery' => true,
			'publish_gallery' => true,
			'read' => true,
			'upload_files' => true,
		)
	);
}
register_activation_hook( __FILE__, 'rpg_add_product_management_role' );

function rpg_remove_product_management_role() {
	remove_role('product_manager');
}
register_deactivation_hook(__FILE__, 'rpg_remove_product_management_role');

add_action('admin_init','rpg_gallery_add_role_caps',999);
function rpg_gallery_add_role_caps() {
	// Add the roles you'd like to administer the custom post types
	$roles = array('product_manager','administrator');
	
	// Loop through each role and assign capabilities
	foreach($roles as $the_role) { 

		 $role = get_role($the_role);

	  $role->add_cap( 'edit_gallery' ); 
	  $role->add_cap( 'read_gallery' );
	  $role->add_cap( 'delete_gallery' ); 
	  $role->add_cap( 'publish_gallery' ); 

	  $role->add_cap( 'edit_gallerys' );// Note the "s" at the end of plural capabilities
	  $role->add_cap( 'edit_others_gallerys' );
	  $role->add_cap( 'publish_gallerys' );
	  $role->add_cap( 'read_private_gallerys' );
	  $role->add_cap( 'delete_gallerys' );
	  $role->add_cap( 'delete_private_gallerys' );
	  $role->add_cap( 'delete_published_gallerys' );
	  $role->add_cap( 'delete_others_gallerys' );
	  $role->add_cap( 'edit_private_gallerys' );
	  $role->add_cap( 'edit_published_gallerys' );

	  $role->add_cap( 'read_gallerys' ); 
	  $role->add_cap( 'read_private_gallerys' );
	}

}	
?>