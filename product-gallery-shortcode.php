<?php
	function rpg_short_category_gallery( $atts )
	{
		extract(shortcode_atts(array(
			  'width' => '150',
			  'background' => '#fff'
		   ), $atts));	
	
		ob_start();
		tem_category_gallery( $width, $background );
		$product = ob_get_clean();
		return $product;	
	}
	add_shortcode( 'category_gallery','rpg_short_category_gallery');

	function rpg_short_lightbox_product_gallery( $atts )
	{
		extract(shortcode_atts(array(
			  'per_page' => '8',
			  'category' => 'sofa',
			  'background' => '#fff',
			  'desc' => 'false'
		   ), $atts));	
	
		ob_start();
		if ($desc=='false'){
			rpg_tem_lightbox_product_gallery( $per_page, $category, $background );
		}
		else
		{
			rpg_tem_lightbox_product_gallery_short_desc( $per_page, $category, $background );
		}
		$product = ob_get_clean();
		return $product;	
	}
	add_shortcode( 'lightbox_product_gallery','rpg_short_lightbox_product_gallery');

?>