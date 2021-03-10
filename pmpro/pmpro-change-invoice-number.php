/**
 * Paid Memberships Pro change default order number to your favourite format.
 * This code snippet takes the order ID and create an order code from that such as "INVOICE-1", "INVOICE-2", "INVOICE-3" and increment with each order created.
 * A fallback is in place that if "INVOICE-1" already exists for some order, it will just generate a random code to be safe.
 * Add this code to your PMPro Customizations Plugin - https://www.paidmembershipspro.com/create-a-plugin-for-pmpro-customizations/
 * OR you use WP Plugin Code-Snippet as well.
 * www.paidmembershipspro.com
 */

function pmpro_change_order_codes( $code ) {
	
	global $wpdb;

	$lastId= $wpdb->get_var( "SELECT `id` FROM $wpdb->pmpro_membership_orders ORDER BY `id` DESC LIMIT 1" );

	$currentId = (int) $lastId + 1;

	$prefix = apply_filters( "pmpro_custom_order_prefix", "INVOICE" ); // You can add your favourite string as well.

	$code =  $prefix ."-". $currentId; //Code cannot just be an integer and _must_contain_a_string_.

	// We must add some check to look if the order code is not created, otherwise it will be an infinite loop.
	$check = $wpdb->get_var( "SELECT `id` FROM $wpdb->pmpro_membership_orders WHERE code = '$code' LIMIT 1" );

	// If the code exists or is only a number, then generate a random order code with 10 digits.
	if ( $check || is_numeric( $code ) ) {
		$code = wp_generate_password( 10, false, false );

	}

	return $code;

}
add_filter( 'pmpro_random_code', 'pmpro_custom_order_codes', 10, 1 );