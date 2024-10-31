<?PHP
// Register and load the widget
function load_widget() {
	register_widget( 'widget_category_view' );
	register_widget( 'widget_product_view' );
}
add_action( 'widgets_init', 'load_widget' );




// Creating the widget for random category view
class widget_category_view extends WP_Widget {
	function __construct() {
		parent::__construct
		(
		'widget_rnd_category_view', // UNIQUE Base ID of your widget
		__('Category Gallery', 'widget_rnd_category_domain', 'product-gallery'), // Widget name will appear in UI
		array( 'description' => __( 'Random Category Viewer for footer & sidebar', 'widget_rnd_category_domain' , 'product-gallery'), ) // Widget description
		);
	}
	/**
	* Front-end display of widget.
	*
	* @see WP_Widget::widget()
	*
	* @param array $args     Widget arguments.
	* @param array $instance Saved values from database.
	*/
	function widget( $args, $instance ) {
		$title = apply_filters( 'widget_title', $instance['title'] );
		$max_cat = apply_filters('max_cat', $instance['max_cat'] );
		$cat_image_size = apply_filters('cat_image_size', $instance['cat_image_size'] );
		// before and after widget arguments are defined by themes
		echo $args['before_widget'];
		if ( ! empty( $title ) )
		{
			echo $args['before_title'] . $title . $args['after_title'];
		}
		// This is where you run the code and display the output ( e.g. name of template )
		echo rpg_tem_rnd_category($max_cat, $cat_image_size);
		echo $args['after_widget'];
	}
	
	/**
	* Back-end widget form.
	*
	* @see WP_Widget::form()
	*
	* @param array $instance Previously saved values from database.
	*/
	function form( $instance ) {
		$title =  ! empty( $instance['title'] ) ? $instance['title'] : __( 'Gallery', 'widget_rnd_category_domain' , 'product-gallery');
		$max_cat =  ! empty( $instance['max_cat'] ) ? $instance['max_cat'] : __( '3', 'widget_rnd_category_domain' , 'product-gallery');
		$cat_image_size = ! empty($instance['cat_image_size'] ) ? $instance['cat_image_size'] : __( '4', 'widget_rnd_category_domain' , 'product-gallery');
	
	// Widget admin form
	?>
	<p>
	<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' , 'product-gallery'); ?></label> 
	<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
	
	<label for="<?php echo $this->get_field_id( 'max_cat' ); ?>"><?php _e( 'Max. Cat. View:' , 'product-gallery'); ?></label><br />
	<input class="widefat" id="<?php echo $this->get_field_id( 'max_cat' ); ?>" name="<?php echo $this->get_field_name( 'max_cat' ); ?>" type="text" value="<?php echo esc_attr( $max_cat ); ?>" style="width:50px;" /><br />

	<label for="<?php echo $this->get_field_id( 'cat_image_size' ); ?>"><?php _e( 'Image Size:' , 'product-gallery'); ?></label><br />
    <select id="<?php echo $this->get_field_id( 'cat_image_size' ); ?>" name="<?php echo $this->get_field_name( 'cat_image_size' ); ?>">
        <option value="4" <?php echo ($cat_image_size=='4')?'selected':''; ?>>Thumbnail</option>
        <option value="6"  <?php echo ($cat_image_size=='6')?'selected':''; ?>>Small</option>
        <option value="8"  <?php echo ($cat_image_size=='8')?'selected':''; ?>>Medium</option>
        <option value="10"  <?php echo ($cat_image_size=='10')?'selected':''; ?>>Large</option>
        <option value="12"  <?php echo ($cat_image_size=='12')?'selected':''; ?>>Huge</option>
	</select>
	
	</p>
	<?php 
	}
	/**
	* Sanitize widget form values as they are saved.
	*
	* @see WP_Widget::update()
	*
	* @param array $new_instance Values just sent to be saved.
	* @param array $old_instance Previously saved values from database.
	*
	* @return array Updated safe values to be saved.
	*/
	function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		$instance['max_cat'] = ( ! empty( $new_instance['max_cat'] ) ) ? strip_tags( $new_instance['max_cat'] ) : '';
		$instance['cat_image_size'] = ( ! empty( $new_instance['cat_image_size'] ) ) ? strip_tags( $new_instance['cat_image_size'] ) : '';
		return $instance;
	}
} // Class widget_category_view ends here




/*
Creating the widget for random product view
*/
class widget_product_view extends WP_Widget {
	function __construct() {
		parent::__construct
		(
		'widget_rnd_product_view', // UNIQUE Base ID of your widget
		__('Product Gallery', 'widget_rnd_product_domain', 'product-gallery'), // Widget name will appear in UI
		array( 'description' => __( 'Random Product Viewer for footer & sidebar', 'widget_rnd_product_domain' , 'product-gallery'), ) // Widget description
		);
	}
	/**
	* Front-end display of widget.
	*
	* @see WP_Widget::widget()
	*
	* @param array $args     Widget arguments.
	* @param array $instance Saved values from database.
	*/
	function widget( $args, $instance ) {
		$title = apply_filters( 'widget_title', $instance['title'] );
		$max_cat = apply_filters('max_cat', $instance['max_cat'] );
		$new_prod_only = apply_filters('new_prod_only', $instance['new_prod_only'] );
		$open_prod_url = apply_filters('open_prod_url', $instance['open_prod_url'] );
		$image_size = apply_filters('image_size', $instance['image_size'] );
		// before and after widget arguments are defined by themes
		echo $args['before_widget'];
		if ( ! empty( $title ) )
		{
			echo $args['before_title'] . $title . $args['after_title'];
		}
		// This is where you run the code and display the output ( e.g. name of template )
		echo rpg_tem_rnd_product($max_cat, $new_prod_only, $open_prod_url, $image_size );
		echo $args['after_widget'];
	}
	
	/**
	* Back-end widget form.
	*
	* @see WP_Widget::form()
	*
	* @param array $instance Previously saved values from database.
	*/
	function form( $instance ) {
		$title =  ! empty( $instance['title'] ) ? $instance['title'] : __( 'Gallery', 'widget_rnd_product_domain' , 'product-gallery');
		$max_cat =  ! empty( $instance['max_cat'] ) ? $instance['max_cat'] : __( '3', 'widget_rnd_product_domain' , 'product-gallery');
		$new_prod_only =  ! empty( $instance['new_prod_only'] ) ? $instance['new_prod_only'] : __( 'checked', 'widget_rnd_product_domain' , 'product-gallery');
		$open_prod_url = ! empty( $instance['open_prod_url'] ) ? $instance['open_prod_url'] : __( 'checked', 'widget_rnd_product_domain' , 'product-gallery');
		$image_size = ! empty($instance['image_size'] ) ? $instance['image_size'] : __( '4', 'widget_rnd_product_domain' , 'product-gallery');
	// Widget admin form
	?>
	<p>
	<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' , 'product-gallery'); ?></label> 
	<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
	
	<label for="<?php echo $this->get_field_id( 'max_cat' ); ?>"><?php _e( 'Max. Product View:' , 'product-gallery'); ?></label><br />
	<input class="widefat" id="<?php echo $this->get_field_id( 'max_cat' ); ?>" name="<?php echo $this->get_field_name( 'max_cat' ); ?>" type="text" value="<?php echo esc_attr( $max_cat ); ?>" style="width:50px;" /><br />
	
	<label for="<?php echo $this->get_field_id( 'new_prod_only' ); ?>"><?php _e( 'Show New Product Only:' , 'product-gallery'); ?></label><br />
	<input class="widefat" id="<?php echo $this->get_field_id( 'new_prod_only' ); ?>" name="<?php echo $this->get_field_name( 'new_prod_only' ); ?>" type="checkbox" value="true" <?php echo checked( $new_prod_only ); ?> /><br />
    
	<label for="<?php echo $this->get_field_id( 'open_prod_url' ); ?>"><?php _e( 'Open Product URL:' , 'product-gallery'); ?></label><br />
	<input class="widefat" id="<?php echo $this->get_field_id( 'open_prod_url' ); ?>" name="<?php echo $this->get_field_name( 'open_prod_url' ); ?>" type="checkbox" value="true" <?php echo checked( $open_prod_url ); ?> /><br />

	<label for="<?php echo $this->get_field_id( 'image_size' ); ?>"><?php _e( 'Image Size:' , 'product-gallery'); ?></label><br />
    <select id="<?php echo $this->get_field_id( 'image_size' ); ?>" name="<?php echo $this->get_field_name( 'image_size' ); ?>">
        <option value="4" <?php echo ($image_size=='4')?'selected':''; ?>>Thumbnail</option>
        <option value="6"  <?php echo ($image_size=='6')?'selected':''; ?>>Small</option>
        <option value="8"  <?php echo ($image_size=='8')?'selected':''; ?>>Medium</option>
        <option value="10"  <?php echo ($image_size=='10')?'selected':''; ?>>Large</option>
        <option value="12"  <?php echo ($image_size=='12')?'selected':''; ?>>Huge</option>
	</select>

	</p>
	<?php 
	}
	/**
	* Sanitize widget form values as they are saved.
	*
	* @see WP_Widget::update()
	*
	* @param array $new_instance Values just sent to be saved.
	* @param array $old_instance Previously saved values from database.
	*
	* @return array Updated safe values to be saved.
	*/
	function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		$instance['max_cat'] = ( ! empty( $new_instance['max_cat'] ) ) ? strip_tags( $new_instance['max_cat'] ) : '';
		$instance['new_prod_only'] = ( ! empty( $new_instance['new_prod_only'] ) ) ? true : false;
		$instance['open_prod_url'] = ( ! empty( $new_instance['open_prod_url'] ) ) ? true : false;
		$instance['image_size'] = ( ! empty( $new_instance['image_size'] ) ) ? strip_tags( $new_instance['image_size'] ) : '';
		return $instance;
	}
} // Class widget_product_view ends here

?>