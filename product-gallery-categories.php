<?php
$tax = 'categories';
$post = 'gallery';

add_action( 'init', 'rpg_build_taxonomies', 0 );
function rpg_build_taxonomies() {
	global $tax,$post;
	$field_args = array(
	'labels' => array(
	'name'              => 'Categories', 'taxonomy general name',
	'singular_name'     => 'Category', 'taxonomy singular name',
	'search_items'      => 'Search Categories',
	'all_items'         => 'All Categories',
	'parent_item'       => 'Parent Category',
	'parent_item_colon' => 'Parent Category:',
	'edit_item'         => 'Edit Category',
	'update_item'       => 'Update Category',
	'add_new_item'      => 'Add New Category',
	'new_item_name'     => 'New Category',
	'menu_name'         => 'Categories',
	),
	'hierarchical' => true,
	'rewrite' => false, 
	'query_var' => true,
	'capabilities' => array(
      'manage_terms'=> 'manage_options',
      'edit_terms'=> 'manage_options',
      'delete_terms'=> 'manage_options',
      'assign_terms' => 'edit_gallery'
    ),
  );
  register_taxonomy($tax, array($post), $field_args);
}


// Filter the request to just give posts for the given taxonomy, if applicable.
function rpg_taxonomy_filter_restrict_manage_posts() {
	global $typenow;
	$post_type = 'gallery'; // change HERE
	$taxonomy = 'categories'; // change HERE

	if ($typenow == $post_type) {
		$selected = isset($_GET[$taxonomy]) ? $_GET[$taxonomy] : '';
		$info_taxonomy = get_taxonomy($taxonomy);
		wp_dropdown_categories(array(
			'show_option_all' => __("Show All {$info_taxonomy->label}", 'product-gallery'),
			'taxonomy' => $taxonomy,
			'name' => $taxonomy,
			'orderby' => 'name',
			'selected' => $selected,
			'hierarchical' => true,
			'show_count' => true,
			'hide_empty' => true,
		));
	};
}
add_action( 'restrict_manage_posts', 'rpg_taxonomy_filter_restrict_manage_posts');

function rpg_convert_id_to_term_in_query($query) {
	global $pagenow;
	$post_type = 'gallery'; // change HERE
	$taxonomy = 'categories'; // change HERE
	$q_vars = &$query->query_vars;
	if ($pagenow == 'edit.php' && isset($q_vars['post_type']) && $q_vars['post_type'] == $post_type && isset($q_vars[$taxonomy]) && is_numeric($q_vars[$taxonomy]) && $q_vars[$taxonomy] != 0) {
		$term = get_term_by('id', $q_vars[$taxonomy], $taxonomy);
		$q_vars[$taxonomy] = $term->slug;
	}
}
add_filter('parse_query', 'rpg_convert_id_to_term_in_query');


add_action('admin_head', 'wpds_admin_head');
add_action('edit_term', 'wpds_save_tax_pic');
add_action('create_term', 'wpds_save_tax_pic');
function wpds_admin_head() {
	//    $taxonomies = get_taxonomies();
    $taxonomies = array('categories'); // uncomment and specify particular taxonomies you want to add image feature.
    if (is_array($taxonomies)) {
        foreach ($taxonomies as $z_taxonomy) {
            add_action($z_taxonomy . '_add_form_fields', 'wpds_tax_field');
            add_action($z_taxonomy . '_edit_form_fields', 'wpds_tax_field');
        }
    }
}

// add image field in add form
function wpds_tax_field($taxonomy) {
    wp_enqueue_style('thickbox');
    wp_enqueue_script('thickbox');
    if(empty($taxonomy)) {
        echo '<div class="form-field">
                <label for="wpds_tax_pic">Picture</label>
                <input type="text" name="wpds_tax_pic" id="wpds_tax_pic" value="" />
			  </div>';
			
    }
    else{
        $wpds_tax_pic_url = get_option('wpds_tax_pic' . $taxonomy->term_id);
        echo '<tr class="form-field">
		<th scope="row" valign="top"><label for="wpds_tax_pic">Picture</label></th>
		<td><input type="text" name="wpds_tax_pic" id="wpds_tax_pic" value="' . $wpds_tax_pic_url . '" /><br />';
        if(!empty($wpds_tax_pic_url))
            echo '<img src="'.$wpds_tax_pic_url.'" style="max-width:200px;border: 1px solid #ccc;padding: 5px;box-shadow: 5px 5px 10px #ccc;margin-top: 10px;" >';
        echo '</td></tr>';
    }
    echo '<script type="text/javascript">
	    jQuery(document).ready(function() {
                jQuery("#wpds_tax_pic").click(function() {
                    tb_show("", "media-upload.php?type=image&amp;TB_iframe=true");
                    return false;
                });
                window.send_to_editor = function(html) {
                    jQuery("#wpds_tax_pic").val( jQuery("img",html).attr("src") );
                    tb_remove();
                }
	    });
	</script>';
	$dl = get_option('product_page' . $taxonomy->term_id);
	$pagelistargs = array(
	'child_of'     => 0,
	'sort_order'   => 'ASC',
	'sort_column'  => 'post_title',
	'hierarchical' => 1,
	'post_type' => 'page',
	);
	echo '<tr class="form-field">
	<th scope="row" valign="top"><label for="product_page">Page URL</label></th>
	<td>
	<select name="product_page" id="product_page"> 
 <option value="">' . esc_attr( __( 'Select page' , 'product-gallery') ) . '</option>';
  $pages = get_pages($pagelistargs); 
  foreach ( $pages as $page ) {
	if ($page->ID == $dl) { $selected='selected'; } else { $selected=''; }
  	$option = '<option value="' . $page->ID . '"';
	$option .= $selected;
	$option .= ">";
	$option .= $page->post_title;
	$option .= '</option>';
	echo $option;
  }
  echo '</select></td></tr>';
		
}

// save our taxonomy image while edit or save term
function wpds_save_tax_pic($term_id) {
    if (isset($_POST['wpds_tax_pic']))
        update_option('wpds_tax_pic' . $term_id, $_POST['wpds_tax_pic']);
    if (isset($_POST['product_page']))
        update_option('product_page' . $term_id, $_POST['product_page']);
}

// output taxonomy image url for the given term_id (NULL by default)
function wpds_tax_pic_url($term_id = NULL) {
    if ($term_id) 
        return get_option('wpds_tax_pic' . $term_id);
    elseif (is_category())
        return get_option('wpds_tax_pic' . get_query_var('cat')) ;
    elseif (is_tax()) {
        $current_term = get_term_by('slug', get_query_var('term'), get_query_var('taxonomy'));
        return get_option('wpds_tax_pic' . $current_term->term_id);
    }
}

//For add the new fields 
function my_column_header($columns)
{
    $columns['customval'] = __('Picture', 'product-gallery');
    return $columns;
}
add_filter('manage_edit-categories_columns', 'my_column_header', 10, 2);


//Get the custom field value
function my_column_value($empty = '', $custom_column, $term_id)
{
echo '<img src="'. wpds_tax_pic_url($term_id).'" style="max-width:200px;border: 1px solid #ccc;padding: 5px;box-shadow: 5px 5px 10px #ccc;margin-top: 10px;" >'; 
}
add_filter('manage_categories_custom_column', 'my_column_value', 10, 3)

?>