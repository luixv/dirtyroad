

How to add data to the Map pin popup.

In this example, we will add the data from a profile field called 'Phone' to the popup.

First create a template overload in your theme or child-theme by following the directions at the top of this file:
bp-maps-for-members/templates/members/members-map-item.php

In that template, add the $phone variable, something like:

	<p>

		<?php if ( isset( $phone ) ) echo $phone; ?>

	</p>


Then in your theme/functions.php or in plugins/bp-custom.php, add this filter function:

	function pp_mm_item_extra( $args, $member_id ) {

		$phone = xprofile_get_field_data( 'Phone', $member_id, 'comma' );

		if ( ! empty( $phone ) ) {

			$args['phone'] = $phone;

		}

		return $args;

	}
	add_filter( 'pp_mm_item_filter', 'pp_mm_item_extra', 10, 2 );


You can use the same function to add as many fields as you want.

