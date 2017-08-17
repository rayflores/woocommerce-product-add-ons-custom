<?php foreach ( $addon['options'] as $i => $option ) :

	$price = apply_filters( 'woocommerce_product_addons_option_price',
		$option['price'] > 0 ? '(' . wc_price( get_product_addon_price_for_display( $option['price'] ) ) . ')' : '',
		$option,
		$i,
		'checkbox'
	);

	$selected = isset( $_POST[ 'addon-' . sanitize_title( $addon['field-name'] ) ] ) ? $_POST[ 'addon-' . sanitize_title( $addon['field-name'] ) ] : array();
	if ( ! is_array( $selected ) ) {
		$selected = array( $selected );
	}

	$current_value = ( in_array( sanitize_title( $option['label'] ), $selected ) ) ? 1 : 0;

	// Image label
	$image = $image_class = null;
	if (is_numeric($option['image'])) {
		$image = wp_get_attachment_image($option['image'], 'option');
		$image_src = wp_get_attachment_image_src($option['image'], 'option');
		$image_class = 'addon-has-image';
	}

	?>


	<p class="form-row form-row-wide this addon-wrap-<?php echo sanitize_title( $addon['field-name'] ) . '-' . $i; ?> <?php echo $image_class; ?>">
		<input type="checkbox" id="<?php echo sanitize_title( $option['label'] ); ?>" class="addon addon-checkbox pac" name="addon-<?php echo sanitize_title( $addon['field-name'] ); ?>[]" data-raw-price="<?php echo esc_attr( $option['price'] ); ?>" data-price="<?php echo get_product_addon_price_for_display( $option['price'] ); ?>" value="<?php echo sanitize_title( $option['label'] ); ?>" <?php checked( $current_value, 1 ); ?> />
		<label for="<?php echo sanitize_title( $option['label'] ); ?>" style="background:url(<?php echo $image_src[0]; ?>)no-repeat;color:#fff;">
			<?php echo $option['label'] . ' ' . $price ; ?>
		</label>
	</p>
	

<?php endforeach; ?>
<script>
	jQuery('input.pac').on('change', function() {
		jQuery('input.pac').not(this).prop('checked', false);  
	});
	</script>