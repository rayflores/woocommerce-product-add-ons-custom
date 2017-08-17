<?php 
/* Plugin Name: WooCommerce Product Add-ons Custom */
/**
 * Add custom addon field
 */
function surgeon_add_checkbox_image_field($post, $product_addons, $loop, $option) {
    wp_enqueue_media();
    ob_start();
    ?>
    <td class="checkbox_column">
        <input type="hidden" name="product_addon_option_image[<?php echo $loop; ?>][]" value="<?php echo esc_attr( $option['image']  ); ?>" class="image_attachment_id" />
        <?php if (is_numeric($option['image'])) { 
            $image_src = wp_get_attachment_image_src($option['image']);
            ?>
            <img class="image-preview" src="<?php echo $image_src[0]; ?>" width="60" height="60" style="max-height: 60px; width: 60px;">
        <?php } ?>
        <input type="button" class="button upload_image_button" value="<?php _e( 'Upload image' ); ?>" />
    </td>
    <?php
    $output = ob_get_clean();
    echo $output;

}
add_action('woocommerce_product_addons_panel_option_row', 'surgeon_add_checkbox_image_field', 10, 4);
/**
 * Add checkbox headings to addon fields
 */
function surgeon_add_checkbox_heading_fields($post, $addon, $loop) {
    echo '<th class="checkbox_column"><span class="column-title">Image</span></th>';
}
add_action('woocommerce_product_addons_panel_option_heading', 'surgeon_add_checkbox_heading_fields', 10, 3);
/**
 * Admin scripts
 */
function surgeon_admin_scripts_and_styles($hook) {
    wp_enqueue_script( 'surgeon-admin', plugins_url('surgeon-admin.js',__FILE__) );
    global $post;
    wp_localize_script('surgeon-admin', 'post', array(
            'post_id' => $post->ID
        )
    );
}
add_action( 'admin_enqueue_scripts', 'surgeon_admin_scripts_and_styles' );
/**
 * Save custom addon field
 */
function surgeon_save_checkbox_image_field($data, $i) {
    $addon_option_image = $_POST['product_addon_option_image'][$i];
    for ( $ii = 0; $ii < sizeof( $data['options'] ); $ii++ ) {
        $image    = sanitize_text_field( stripslashes( $addon_option_image[ $ii ] ) );
        $data['options'][$ii]['image'] = $image;
    }
    return $data;
}
add_filter('woocommerce_product_addons_save_data', 'surgeon_save_checkbox_image_field', 10, 2);

function wc_product_addons_custom_plugin_path() {
  // gets the absolute path to this plugin directory
  return untrailingslashit( plugin_dir_path( __FILE__ ) );
}
add_filter( 'woocommerce_locate_template', 'wc_product_addons_custom_locate_template', 10, 3 );
function wc_product_addons_custom_locate_template( $template, $template_name, $template_path ) {
  global $woocommerce; 
  $_template = $template;
 
  if ( ! $template_path ) {
	  $template_path = $woocommerce->template_url;
  }
  $plugin_path  = wc_product_addons_custom_plugin_path() . '/woocommerce/';

  // Look within passed path within the theme - this is priority
  $template = locate_template(
     array(
      $template_path . $template_name,
       $template_name
     )
  );

  // Modification: Get the template from this plugin, if it exists
  if (  file_exists(  $plugin_path . $template_name ) ) {
     $template = $plugin_path . $template_name;
   }
 // Use default template

  if ( ! $template ) {
    $template = $_template;
  }

  // Return what we found
  return $template;
}

add_action('wp_enqueue_scripts', 'wc_products_addon_custom_enqueue_styles');
function wc_products_addon_custom_enqueue_styles(){
		wp_enqueue_style( 'pac-css', plugins_url('wc_pac.css',__FILE__) );
		// wp_enqueue_script( 'pac-js', plugins_url('js/wc_pac_script.js',__FILE__) );
		// wp_localize_script( 'wc_pac_params', );
}