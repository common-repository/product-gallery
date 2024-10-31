<?php
// Create Custom Post Type
    function rpg_register_gallery_posttype() {
        $labels = array(
            'name'              => _x( 'Product Gallery', 'post type general name', 'product-gallery'),
            'singular_name'     => _x( 'Product Gallery', 'post type singular name', 'product-gallery'),
			'menu_name'         => __( 'Gallery', 'product-gallery'),
            'parent_item_colon' => __( 'Parent Item:', 'product-gallery'),
			'all_items'         => __( 'All Items', 'product-gallery'),
            'view_item'         => __( 'View Image', 'product-gallery'),
            'add_new_item'      => __( 'Add New Image', 'product-gallery'),
            'add_new'           => __( 'Add New Image', 'product-gallery'),
            'edit_item'         => __( 'Edit Image', 'product-gallery'),
			'update_item'       => __( 'Update Image', 'product-gallery'),
            'search_items'      => __( 'Search Images', 'product-gallery'),
            'not_found'         => __( 'Not Found', 'product-gallery'),
            'not_found_in_trash'=> __( 'Not Found in Trash', 'product-gallery'),
        );
 
        $taxonomies = array('');
 
        $supports = array('title','thumbnail');
 
        $post_type_args = array(
		    'label'               => __( 'gallery', 'product-gallery'),
            'labels'              => $labels,
            'supports'            => $supports,
            'taxonomies'          => $taxonomies,
            'hierarchical'        => false,
            'public'              => true,
            'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_nav_menus'   => true,
			'show_in_admin_bar'   => true,
			'menu_position'       => 28, // Where it is in the menu. Change to 6 and it's below posts. 11 and it's below media, etc.
			'can_export'          => true,
			'has_archive'         => false,
			'exclude_from_search' => false,
			'publicly_queryable'  => true,
			'capability_type'     => array('gallery','gallerys'), //'post',
			'map_meta_cap'        => true,
            'singular_label'      => __('Product Gallery', 'product-gallery'),
            'query_var'           => true,
            'rewrite'             => array( 'slug' => 'gallery', 'with_front' => false ),
            'menu_icon'           => plugins_url('images/icon.png', __FILE__),
        );
        register_post_type('gallery',$post_type_args);
    }
    add_action('init', 'rpg_register_gallery_posttype');


// Meta Box for product URL
    $gallery_metabox = array( 
        'id' => 'productlink',
        'title' => 'Product Link',
        'page' => array('gallery'),
        'context' => 'normal',
        'priority' => 'default',
        'fields' => array(
                    array(
                        'name'          => 'Product URL',
                        'desc'          => '',
                        'id'            => 'galleryurl',
                        'class'         => 'galleryurl',
                        'type'          => 'text',
                        'rich_editor'   => 0,            
                        'max'           => -1             
                    ),
					array(
                        'name'          => 'New Products',
                        'desc'          => '',
                        'id'            => 'newprod',
                        'class'         => 'newprod',
                        'type'          => 'chk',
                        'rich_editor'   => 0,
                        'max'           => 0
                    ),
					array(
                        'name'          => 'Short Desc.',
                        'desc'          => '',
                        'id'            => 'prodshortdesc',
                        'class'         => 'prodshortdesc',
                        'type'          => 'textarea',
                        'rich_editor'   => 0,
                        'max'           => -1
                    ),
					array(
                        'name'          => 'Rate',
                        'desc'          => '',
                        'id'            => 'prodrate',
                        'class'         => 'prodrate',
                        'type'          => 'text',
                        'rich_editor'   => 0,
                        'max'           => 8
                    ),
                    )
	    );
                 
    add_action('admin_menu', 'rpg_add_gallerylink_meta_box');
    function rpg_add_gallerylink_meta_box() {
     
        global $gallery_metabox;
		
        foreach($gallery_metabox['page'] as $page) {
			add_meta_box($gallery_metabox['id'], $gallery_metabox['title'], 'rpg_show_gallery_box', $page, $gallery_metabox['context'], $gallery_metabox['priority'], $gallery_metabox);
        }
    }

     
// function to show meta boxes
    function rpg_show_gallery_box()  {
		
        global $post;
        global $gallery_metabox;
        global $wptuts_prefix;
        global $wp_version;

        // Use nonce for verification
        echo '<input type="hidden" name="gallery_meta_box_nonce" value="', wp_create_nonce(basename(__FILE__)), '" />';
         
        echo '<table class="form-table">';
     
        foreach ($gallery_metabox['fields'] as $field) {
            // get current post meta data
     
            $meta = get_post_meta($post->ID, $field['id'], true);
            echo '<tr>',
                    '<th style="width:20%"><label for="', $field['id'], '">', stripslashes($field['name']), '</label></th>',
                    '<td class="gallery_field_type_' . str_replace(' ', '_', $field['type']) . '">';
            switch ($field['type']) {

                case 'text':
                    echo '<input type="text" name="', $field['id'], '" id="', $field['id'], '" value="', $meta ? $meta : $field['std'], '" size="30" style="width:97%" maxlength="' . $field['max'] . '" /><br/>', '', stripslashes($field['desc']);
                    break;

                case 'chk':
                    echo '<input type="checkbox" name="', $field['id'], '" id="', $field['id'], '" value="true"' , $meta ? 'checked' : '' , ' "/><br/>', '', stripslashes($field['desc']);
                    break;

                case 'textarea':
					wp_editor($meta ? $meta : $field['std'], $field['id'], array(
            'wpautop'       =>      true,
            'media_buttons' =>      false,
            'textarea_name' =>      $field['id'],
            'textarea_rows' =>      10,
            'teeny'         =>      false,
			'tinymce'       =>      true
            ));
                    break;
            }
            echo    '<td>',
                '</tr>';
        }
         
        echo '</table>';
    }   
     
    // Save data from meta box
    function rpg_gallery_save($post_id) {
        global $post;
        global $gallery_metabox;
         
        // verify nonce
        if (!wp_verify_nonce($_POST['gallery_meta_box_nonce'], basename(__FILE__))) {
            return $post_id;
        }
     
        // check autosave
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return $post_id;
        }
     
        // check permissions
        if ('page' == $_POST['post_type']) {
            if (!current_user_can('edit_page', $post_id)) {
                return $post_id;
            }
        } elseif (!current_user_can('edit_post', $post_id)) {
            return $post_id;
        }
         
        foreach ($gallery_metabox['fields'] as $field) {
         
            $old = get_post_meta($post_id, $field['id'], true);
            $new = $_POST[$field['id']];
             
            if ($new && $new != $old) {
/* CHANGE HERE */
				switch ($field['type']) {
					 case 'text':
						 update_post_meta($post_id, $field['id'], $new);
						 break;
						 
					 case 'chk':
						$my_chk = $_POST[$field['id']] ? true : false;
						update_post_meta($post_id, $field['id'], $my_chk);
						break;

					 case 'textarea':
						 update_post_meta($post_id, $field['id'], $new);
						 break;
				}
            } elseif ('' == $new && $old) {
                delete_post_meta($post_id, $field['id'], $old);
            }
        }
    }
    add_action('save_post', 'rpg_gallery_save');

// Custom Categories
/*
add_action( 'init', 'build_taxonomies', 0 );
function build_taxonomies() {
    register_taxonomy( 'categories', array('gallery'), array( 'hierarchical' => true, 'label' => 'Categories', 'query_var' => true, 'rewrite' => true ) );
}
*/

// GET FEATURED IMAGE
function rpg_gallery_get_featured_image($post_ID) {
    $post_thumbnail_id = get_post_thumbnail_id($post_ID);
    if ($post_thumbnail_id) {
        $post_thumbnail_img = wp_get_attachment_image_src($post_thumbnail_id, 'thumbnail');
        return $post_thumbnail_img[0];
    }
}

// ADD NEW COLUMN
function rpg_gallery_columns_head($defaults) {
/*	$defaults['group'] = 'Categories'; 
    $defaults['featured_image'] = 'Featured Image'; */
	
	$defaults = array(
		"title" => "Title",
		"date" => "Date",
		'group' => 'Categories',
		'featured_image' => 'Image'
	);
    return $defaults;
}
 
// SHOW THE FEATURED IMAGE
function rpg_gallery_columns_content($column_name) {
    if ($column_name == 'featured_image') {
        $post_featured_image = rpg_gallery_get_featured_image($post_ID);
        if ($post_featured_image) {
            echo '<img src="' . $post_featured_image . '" />';
        }
    }
	
    if ($column_name == 'group') {
		$terms = get_the_term_list( $post_ID , 'categories' , '' , ',' , '' );
		echo $terms;
	}
	
}
add_filter('manage_gallery_posts_columns', 'rpg_gallery_columns_head');
add_action('manage_gallery_posts_custom_column', 'rpg_gallery_columns_content');
?>