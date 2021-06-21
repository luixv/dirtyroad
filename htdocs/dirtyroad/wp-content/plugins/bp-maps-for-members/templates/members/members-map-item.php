<?php

/**
 * Members Directory Map Item Template - For Item Popup on Map
 *
 * You can copy this file to your-theme/buddypress/members/
 * and then edit the layout.
 *
 * For an example of adding another field to this template,
 * please see: bp-maps-for-members/readme-add-popup-field.txt
 *
 */

?>

<div class="members-map-pin-popup">

	<?php if ( isset( $avatar ) ) echo $avatar; ?>

	<?php if ( isset( $title ) ) echo $title; ?>

	<p>
		<?php if ( isset( $address ) ) echo $address; ?>
	</p>

	
	<p>
	<b>
		<?php if ( isset( $available ) ) echo $available . ','; ?>
		<?php if ( isset( $guests ) ) echo $guests . ' guest(s)';  ?>
	</b>
	</p>
	
</div>

